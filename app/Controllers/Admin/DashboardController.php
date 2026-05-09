<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = db_connect();
        $activityFilter = strtolower((string) ($this->request->getGet('activity_filter') ?? 'all'));
        $showAllActivities = (string) ($this->request->getGet('show_all') ?? '') === '1';
        $activityPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $activityPerPage = 10;
        $activityPayload = $this->getRecentActivitiesPayload(
            $db,
            $activityFilter,
            $showAllActivities ? null : $activityPerPage,
            $activityPage
        );
        $goldStats = $this->getGoldStats($db);

        $data = [
            'metrics' => [
                'total_users' => $this->countUsers($db),
                'active_subscriptions' => $this->countActiveSubscriptions($db),
                'monthly_revenue' => $this->sumMonthlyRevenue($db),
                'gold_users' => $this->countGoldUsers($db),
            ],
            'gold_stats' => $goldStats,
            'recent_activities' => $activityPayload['rows'],
            'weekly_stats' => $this->getWeeklyStats($db),
            'activity_filter' => $activityFilter,
            'show_all_activities' => $showAllActivities,
            'activity_page' => $activityPage,
            'activity_per_page' => $activityPerPage,
            'activity_total' => $activityPayload['total'],
            'activity_total_pages' => $activityPayload['total_pages'],
        ];

        return view('admin/dashboard', $data);
    }

    public function ajaxActivities()
    {
        $db = db_connect();
        $activityFilter = strtolower((string) ($this->request->getGet('activity_filter') ?? 'all'));
        $showAllActivities = (string) ($this->request->getGet('show_all') ?? '') === '1';
        $activityPage = max(1, (int) ($this->request->getGet('page') ?? 1));
        $activityPerPage = 10;

        $payload = $this->getRecentActivitiesPayload(
            $db,
            $activityFilter,
            $showAllActivities ? null : $activityPerPage,
            $activityPage
        );

        return $this->response->setJSON([
            'success' => true,
            'rowsHtml' => view('admin/partials/activity_table_rows', [
                'recentActivities' => $payload['rows'],
            ]),
            'pagerHtml' => view('admin/partials/activity_pager', [
                'activityFilter' => $activityFilter,
                'showAllActivities' => $showAllActivities,
                'activityPage' => $activityPage,
                'activityTotalPages' => $payload['total_pages'],
                'activityTotal' => $payload['total'],
                'activityPerPage' => $activityPerPage,
            ]),
            'summary' => [
                'total' => $payload['total'],
                'page' => $activityPage,
                'total_pages' => $payload['total_pages'],
                'show_all' => $showAllActivities,
            ],
        ]);
    }

    private function countUsers($db): int
    {
        if (! $db->tableExists('users')) {
            return 0;
        }

        return (int) $db->table('users')
            ->where('is_admin', 0)
            ->countAllResults();
    }

    private function countActiveSubscriptions($db): int
    {
        if (! $db->tableExists('user_regimes')) {
            return 0;
        }

        return (int) $db->table('user_regimes')
            ->where('is_active', 1)
            ->countAllResults();
    }

    private function sumMonthlyRevenue($db): float
    {
        if (! $db->tableExists('user_regimes')) {
            return 0.0;
        }

        $result = $db->table('user_regimes')
            ->selectSum('price_paid', 'total')
            ->where('YEAR(purchased_at)', date('Y'))
            ->where('MONTH(purchased_at)', date('m'))
            ->get()
            ->getRowArray();

        return (float) ($result['total'] ?? 0);
    }

    private function countGoldUsers($db): int
    {
        if (! $db->tableExists('users')) {
            return 0;
        }

        return (int) $db->table('users')
            ->where('is_gold', 1)
            ->countAllResults();
    }

    private function getGoldStats($db): array
    {
        if (! $db->tableExists('users')) {
            return [
                'gold_users' => 0,
                'retention_rate' => 0,
                'avg_ltv' => 0,
                'support_score' => 0,
            ];
        }

        $goldUsers = (int) $db->table('users')->where('is_gold', 1)->countAllResults();

        if ($goldUsers === 0) {
            return [
                'gold_users' => 0,
                'retention_rate' => 0,
                'avg_ltv' => 0,
                'support_score' => 0,
            ];
        }

        $activeGoldUsers = (int) $db->table('users')
            ->where('is_gold', 1)
            ->where('is_active', 1)
            ->countAllResults();

        $retentionRate = (int) round(($activeGoldUsers / $goldUsers) * 100);

        $avgLtv = 0.0;
        if ($db->tableExists('user_regimes')) {
            $ltvRow = $db->table('user_regimes ur')
                ->select('AVG(ur.price_paid) AS avg_ltv')
                ->join('users u', 'u.id = ur.user_id', 'inner')
                ->where('u.is_gold', 1)
                ->get()
                ->getRowArray();

            $avgLtv = (float) ($ltvRow['avg_ltv'] ?? 0);
        }

        $last7DaysGoldLogins = (int) $db->table('users')
            ->where('is_gold', 1)
            ->where('last_login >=', date('Y-m-d H:i:s', strtotime('-6 days')))
            ->countAllResults();

        $supportScore = round(min(5, ($last7DaysGoldLogins / $goldUsers) * 5), 1);

        return [
            'gold_users' => $goldUsers,
            'retention_rate' => $retentionRate,
            'avg_ltv' => $avgLtv,
            'support_score' => $supportScore,
        ];
    }

    private function getRecentActivitiesPayload($db, string $activityFilter = 'all', ?int $limit = 10, int $page = 1): array
    {
        if (! $db->tableExists('users')) {
            return [
                'rows' => [],
                'total' => 0,
                'total_pages' => 0,
            ];
        }

        $builder = $this->buildRecentActivitiesBuilder($db, $activityFilter);
        $total = (int) (clone $builder)->countAllResults();

        if ($limit !== null) {
            $offset = max(0, ($page - 1) * $limit);
            $builder->limit($limit, $offset);
        }

        $rows = $builder->orderBy('u.last_login', 'DESC')
            ->get()
            ->getResultArray();

        return [
            'rows' => $this->normalizeRecentActivities($rows),
            'total' => $total,
            'total_pages' => $limit !== null ? (int) ceil($total / $limit) : 1,
        ];
    }

    private function buildRecentActivitiesBuilder($db, string $activityFilter = 'all')
    {
        $hasUserRegimes = $db->tableExists('user_regimes');
        $hasRegimes = $db->tableExists('regimes');

        $builder = $db->table('users u')
            ->select('u.id, u.name, u.email, u.gender, u.height_cm, u.weight_kg, u.age, u.objective, u.imc_value, u.imc_category, u.wallet_balance, u.is_gold, u.is_admin, u.is_active, u.created_at, u.updated_at, u.last_login');

        if ($hasUserRegimes) {
            $builder->select('ur.is_active AS regime_is_active')
                ->join(
                    'user_regimes ur',
                    'ur.id = (SELECT ur2.id FROM user_regimes ur2 WHERE ur2.user_id = u.id ORDER BY ur2.purchased_at DESC, ur2.id DESC LIMIT 1)',
                    'left'
                );
        } else {
            $builder->select('NULL AS regime_is_active', false);
        }

        if ($hasUserRegimes && $hasRegimes) {
            $builder->select('r.name AS plan_name')
                ->join('regimes r', 'r.id = ur.regime_id', 'left');
        } else {
            $builder->select('NULL AS plan_name', false);
        }

        $builder->where('u.is_admin', 0);

        if ($activityFilter === 'active') {
            if ($hasUserRegimes) {
                $builder->where('ur.is_active', 1);
            } else {
                $builder->where('u.is_active', 1);
            }
        } elseif ($activityFilter === 'inactive') {
            if ($hasUserRegimes) {
                $builder->where('ur.is_active', 0);
            } else {
                $builder->where('u.is_active', 0);
            }
        }

        return $builder;
    }

    private function normalizeRecentActivities(array $rows): array
    {
        $objectiveLabels = [
            'augmenter_poids' => 'Prise de poids',
            'reduire_poids' => 'Perte de poids',
            'imc_ideal' => 'IMC idéal',
        ];

        foreach ($rows as &$row) {
            $objective = (string) ($row['objective'] ?? '');
            $objectiveLabel = $objectiveLabels[$objective] ?? ($objective !== '' ? ucwords(str_replace('_', ' ', $objective)) : 'Objectif non défini');
            $planName = trim((string) ($row['plan_name'] ?? ''));
            $isActive = array_key_exists('regime_is_active', $row) && $row['regime_is_active'] !== null
                ? (int) $row['regime_is_active']
                : (int) ($row['is_active'] ?? 0);

            $row['objective_label'] = $objectiveLabel;
            $row['plan_label'] = $planName !== '' ? $planName : $objectiveLabel;
            $row['plan_hint'] = $planName !== '' ? $objectiveLabel : 'Aucun plan acheté';
            $row['is_active'] = $isActive;
        }

        unset($row);

        return $rows;
    }

    // private function getRecentActivities($db, string $activityFilter = 'all', ?int $limit = 10): array
    // {
    //     return $this->getRecentActivitiesPayload($db, $activityFilter, $limit, 1)['rows'];
    // }

    private function getWeeklyStats($db): array
    {
        $weeklyRevenues = [];
        $weeklyInscriptions = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $weeklyRevenues[$date] = 0;
            $weeklyInscriptions[$date] = 0;
        }

        if ($db->tableExists('user_regimes')) {
            $revenues = $db->table('user_regimes ur')
                ->select("DATE(ur.purchased_at) as purchase_date, SUM(ur.price_paid) as total_revenue")
                ->where('ur.purchased_at >=', date('Y-m-d H:i:s', strtotime('-6 days')))
                ->groupBy("DATE(ur.purchased_at)")
                ->get()
                ->getResultArray();

            foreach ($revenues as $row) {
                $date = (string) ($row['purchase_date'] ?? '');
                if ($date && isset($weeklyRevenues[$date])) {
                    $weeklyRevenues[$date] = (float) ($row['total_revenue'] ?? 0);
                }
            }
        }

        if ($db->tableExists('users')) {
            $inscriptions = $db->table('users')
                ->select("DATE(created_at) as signup_date, COUNT(id) as total_signups")
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-6 days')))
                ->groupBy("DATE(created_at)")
                ->get()
                ->getResultArray();

            foreach ($inscriptions as $row) {
                $date = (string) ($row['signup_date'] ?? '');
                if ($date && isset($weeklyInscriptions[$date])) {
                    $weeklyInscriptions[$date] = (int) ($row['total_signups'] ?? 0);
                }
            }
        }

        return [
            'revenues' => $weeklyRevenues,
            'inscriptions' => $weeklyInscriptions,
        ];
    }
}
