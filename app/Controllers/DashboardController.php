<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Activity;
use App\Models\Regime;
use App\Models\User;
use App\Models\UserRegime;
use App\Models\Wallet;

class DashboardController extends BaseController
{
    protected User $userModel;
    protected Wallet $walletModel;
    protected Regime $regimeModel;
    protected Activity $activityModel;
    protected UserRegime $userRegimeModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->walletModel = new Wallet();
        $this->regimeModel = new Regime();
        $this->activityModel = new Activity();
        $this->userRegimeModel = new UserRegime();
    }

    public function index()
    {
        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Utilisateur introuvable.');
        }

        $objectiveLabels = [
            'augmenter_poids' => 'Prendre du poids',
            'reduire_poids' => 'Perte de poids',
            'imc_ideal' => 'IMC idéal',
        ];

        $objective = (string) ($user['objective'] ?? 'imc_ideal');
        $objectiveLabel = $objectiveLabels[$objective] ?? ucfirst(str_replace('_', ' ', $objective));

        $imc = $user['imc_value'] ?? $this->userModel->calculateIMC((float) ($user['weight_kg'] ?? 0), (float) ($user['height_cm'] ?? 0));
        $imcCategory = $user['imc_category'] ?? $this->userModel->getIMCCategory($imc);
        $walletBalance = $this->walletModel->getBalance($userId);

        $currentPlan = 'Standard';
        $activePurchases = $this->userRegimeModel->getActivePurchases($userId);

        if (!empty($activePurchases)) {
            $latestPurchase = $activePurchases[0];
            $currentRegime = $this->regimeModel->find((int) ($latestPurchase['regime_id'] ?? 0));

            if ($currentRegime && !empty($currentRegime['name'])) {
                $currentPlan = $currentRegime['name'];
            }
        }

        $recommendedRegimes = $this->regimeModel->getByObjective($objective);
        if (empty($recommendedRegimes)) {
            $recommendedRegimes = $this->regimeModel->getActiveRegimes();
        }

        if ($imc !== null) {
            usort($recommendedRegimes, function (array $a, array $b) use ($objective, $imc): int {
                $calA = (int) ($a['calorie_target'] ?? 0);
                $calB = (int) ($b['calorie_target'] ?? 0);

                if ($objective === 'reduire_poids' || $imc >= 25) {
                    return $calA <=> $calB;
                }

                if ($objective === 'augmenter_poids' && $imc < 25) {
                    return $calB <=> $calA;
                }

                return abs($calA - 2200) <=> abs($calB - 2200);
            });
        }

        $recommendedActivities = $this->activityModel->getByObjective($objective);
        if (empty($recommendedActivities)) {
            $recommendedActivities = $this->activityModel->getActiveActivities();
        }

        usort($recommendedActivities, function (array $a, array $b) use ($objective, $imc): int {
            $kcalA = (int) ($a['calories_per_hour'] ?? 0);
            $kcalB = (int) ($b['calories_per_hour'] ?? 0);

            if ($objective === 'reduire_poids' || ($imc !== null && $imc >= 25)) {
                return $kcalB <=> $kcalA;
            }

            if ($objective === 'augmenter_poids') {
                return $kcalA <=> $kcalB;
            }

            return abs($kcalA - 250) <=> abs($kcalB - 250);
        });

        $dietImages = [
            base_url('assets/images/image2.png'),
            'https://lh3.googleusercontent.com/aida-public/AB6AXuCombebEqT0bUVQFZdQF2jmJj8rDDDmu8pCSLzmti9-5fGUcc3sd6EjryfgKzuYHOzz2wbNeHzTCQr2pQFwR9pUpfGPygabBzu718KkVGCV_goPMgOK2iof7_bfj8892F5V7jacSwM9DsCWSeAM09yAj3ukqTzYXJPH0c0gAXGfFlox8tIcXJB44bXj01-yzKDye9UtECV8CZH1t58yjXsoWMPyjXpoCO1vg8LmkArUX2ZPiXKaSq6hSuIoD7l0QvfNTeleaiTVDkMx',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuA1WBPPkwhNrQT7hufvbQYy3vUdjoWZiPTMGF5R6MNxRZEpnLJUKPMvWPaSXlzTn0GTRzWMpGtHU7WsMdLSUFZJIvQHM0i12tMiB8wbw08uRMsftERv4AIJrpZQVHcsxzqdhzN3_Ea-WSosplDupbBmdmJLcvAWVTAS7vlvOcKy2d5C70Xw9-JuUTAvgDpZtGKH4PovuIcBbYfvns7mXWsAK0e0mpX61asylfvhRPKvyaWSGYKWYMvUks-qCUu47IGZKwvZfeOMd7nA',
        ];

        $activityImages = [
            'https://lh3.googleusercontent.com/aida-public/AB6AXuCF8wQjrukfWWJYR4S9NgNFYGw8WvF6e5Ye6OyoXEM1lK9HoBCRGk3ZQRdx3MGg4q0Pai59TbwOxCfgI4Uuulcn3gT6wzGGF2X-GogrxxRBykE1dJXI29-J5W3C77gX0jnAgkvn572TdosDGbH1AqYBQmoGkS2JnsTRPD3SzvVhMSWmgi0QvbG4a96-acVZ9EwEdAYM5xsThVqw3MXNWN4_y0IuMR8BkNwcq-s_EKr1WA93YMh28AgjTDlpaxA_zxgz5q6fXnL-p6wJ',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuA-4fa5E4vonRSRCkeYViL-h2yGzyiJ7BVkOAA6L0Sw7dtOOuwpErVMRwTA_IbYDbhM9-H9_T1bsrCZcf0ku5Kami6YyDoftFbb-Zuhz0SKEYjlXJVykNcfA38hvpIQPMwi-2HeonOR9aOoCtcbQGf0CwNunwTv-K-X5hj6BcBrFfou4pc6o-mkBsO-puyv58FzjXBel_Hwdl9yKd9_y1HhlWyUNv3erDdNRA232sRywIwTROcwRebODCHYEgtWOcwahNOEeR2wbbym',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuBqGm6qL8Nn7g7c5D1C5X1K3mB3Yk3M6p1U0wQy2l6J4QwqR2m9S1h6g7a8c9d0e1f2g3h4i5j6k7l8m9n0oPqRStU2vXyZ1a2b3c4d5e6f7g8h9i0j',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuBrm5n3xUu3jV8b9Q0x1A2b3C4d5E6f7G8h9I0j1K2l3m4N5o6P7q8R9s0T1u2V3w4X5y6z7A8b9C0d1E2f3G4h5I6j7K8l9M0n1O2p3q4r5s6t7u8v9w0x',
        ];

        $metrics = [
            [
                'label' => 'IMC actuel',
                'value' => $imc !== null ? number_format((float) $imc, 1) : 'N/A',
                'hint' => $imcCategory ?? 'Non calculé',
                'icon' => 'activity',
                'tone' => 'green',
            ],
            [
                'label' => 'Poids',
                'value' => isset($user['weight_kg']) ? number_format((float) $user['weight_kg'], 0) . ' kg' : 'N/A',
                'hint' => $objectiveLabel,
                'icon' => 'person-standing',
                'tone' => 'neutral',
            ],
            [
                'label' => 'Taille',
                'value' => isset($user['height_cm']) ? number_format((float) $user['height_cm'], 0) . ' cm' : 'N/A',
                'hint' => 'Mesure stable',
                'icon' => 'rulers',
                'tone' => 'neutral',
            ],
        ];

        $diets = [];
        foreach (array_slice($recommendedRegimes, 0, 3) as $index => $regime) {
            $diets[] = [
                'title' => $regime['name'] ?? 'Régime',
                'description' => $regime['description'] ?? 'Programme nutritionnel disponible.',
                'badge' => $index === 0 ? 'Meilleur choix' : 'Recommandé',
                'image' => $dietImages[$index] ?? $dietImages[0],
            ];
        }

        if (empty($diets)) {
            $diets[] = [
                'title' => 'Régime équilibré',
                'description' => 'Aucune recommandation précise pour le moment.',
                'badge' => 'Suggestion',
                'image' => $dietImages[0],
            ];
        }

        $activities = [];
        foreach (array_slice($recommendedActivities, 0, 4) as $index => $activity) {
            $duration = match ((string) ($activity['intensity'] ?? 'medium')) {
                'low' => '15 MIN',
                'high' => '30 MIN',
                default => '20 MIN',
            };

            $activities[] = [
                'title' => $activity['name'] ?? 'Activité',
                'time' => $duration,
                'kcal' => (int) ($activity['calories_per_hour'] ?? 0),
                'image' => $activityImages[$index] ?? $activityImages[0],
            ];
        }

        if (empty($activities)) {
            $activities[] = [
                'title' => 'Marche légère',
                'time' => '20 MIN',
                'kcal' => 120,
                'image' => $activityImages[0],
            ];
        }

        return view('dashboard_user', [
            'metrics' => $metrics,
            'diets' => $diets,
            'activities' => $activities,
            'currentPlan' => $currentPlan,
            'userName' => $user['name'] ?? 'Utilisateur',
            'objectiveLabel' => $objectiveLabel,
            'walletBalance' => $walletBalance,
            'imc' => $imc,
            'imcCategory' => $imcCategory,
            'height' => $user['height_cm'] ?? null,
            'weight' => $user['weight_kg'] ?? null,
            'isGold' => (int) ($user['is_gold'] ?? 0) === 1,
        ]);
    }
}