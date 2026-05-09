<?php
$metrics = $metrics ?? [];
$goldStats = $gold_stats ?? [];
$recentActivities = $recent_activities ?? [];
$weeklyStats = $weekly_stats ?? [];
$activityFilter = $activity_filter ?? 'all';
$showAllActivities = (bool) ($show_all_activities ?? false);
$activityPage = (int) ($activity_page ?? 1);
$activityPerPage = (int) ($activity_per_page ?? 10);
$activityTotal = (int) ($activity_total ?? 0);
$activityTotalPages = (int) ($activity_total_pages ?? 0);

$totalUsers = (int) ($metrics['total_users'] ?? 0);
$activeSubscriptions = (int) ($metrics['active_subscriptions'] ?? 0);
$monthlyRevenue = (float) ($metrics['monthly_revenue'] ?? 0);
$goldUsers = (int) ($metrics['gold_users'] ?? ($goldStats['gold_users'] ?? 0));

$retentionRate = (int) ($goldStats['retention_rate'] ?? 0);
$avgLtv = (float) ($goldStats['avg_ltv'] ?? 0);
$supportScore = (float) ($goldStats['support_score'] ?? 0);

$weeklyRevenues = $weeklyStats['revenues'] ?? [];
$weeklyInscriptions = $weeklyStats['inscriptions'] ?? [];
$revenueData = [];
$inscriptionData = [];
$dayLabels = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dayLabels[] = date('D', strtotime($date));
    $revenueData[] = (float) ($weeklyRevenues[$date] ?? 0);
    $inscriptionData[] = (int) ($weeklyInscriptions[$date] ?? 0);
}

$usersChangePercent = 0;
$subscriptionsChangePercent = 0;
$revenueChangePercent = 0;
$goldUsersChangePercent = 0;

if ($db = db_connect()) {
    $thisWeekUsers = $db->table('users')
        ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-6 days')))
        ->countAllResults();
    $lastWeekUsers = $db->table('users')
        ->where('created_at <', date('Y-m-d H:i:s', strtotime('-6 days')))
        ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-13 days')))
        ->countAllResults();
    if ($lastWeekUsers > 0) {
        $usersChangePercent = round((($thisWeekUsers - $lastWeekUsers) / $lastWeekUsers) * 100, 1);
    }

    $thisWeekRevenue = array_sum($revenueData);
    if ($db->tableExists('user_regimes')) {
        $lastWeekRevenue = $db->table('user_regimes')
            ->selectSum('price_paid', 'total')
            ->where('purchased_at <', date('Y-m-d H:i:s', strtotime('-6 days')))
            ->where('purchased_at >=', date('Y-m-d H:i:s', strtotime('-13 days')))
            ->get()
            ->getRow();
        $lastWeekRevenueVal = (float) ($lastWeekRevenue->total ?? 0);
        if ($lastWeekRevenueVal > 0) {
            $revenueChangePercent = round((($thisWeekRevenue - $lastWeekRevenueVal) / $lastWeekRevenueVal) * 100, 1);
        }
    }

    $thisWeekSubs = 0;
    $lastWeekSubs = 0;
    if ($db->tableExists('user_regimes')) {
        $thisWeekSubs = $db->table('user_regimes')
            ->where('purchased_at >=', date('Y-m-d H:i:s', strtotime('-6 days')))
            ->where('is_active', 1)
            ->countAllResults();
        $lastWeekSubs = $db->table('user_regimes')
            ->where('purchased_at <', date('Y-m-d H:i:s', strtotime('-6 days')))
            ->where('purchased_at >=', date('Y-m-d H:i:s', strtotime('-13 days')))
            ->where('is_active', 1)
            ->countAllResults();
        if ($lastWeekSubs > 0) {
            $subscriptionsChangePercent = round((($thisWeekSubs - $lastWeekSubs) / $lastWeekSubs) * 100, 1);
        }
    }

    if ($db->tableExists('users')) {
        $thisWeekGoldUsers = $db->table('users')
            ->where('is_gold', 1)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-6 days')))
            ->countAllResults();
        $lastWeekGoldUsers = $db->table('users')
            ->where('is_gold', 1)
            ->where('created_at <', date('Y-m-d H:i:s', strtotime('-6 days')))
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-13 days')))
            ->countAllResults();
        if ($lastWeekGoldUsers > 0) {
            $goldUsersChangePercent = round((($thisWeekGoldUsers - $lastWeekGoldUsers) / $lastWeekGoldUsers) * 100, 1);
        }
    }

}

function formatPercent($val)
{
    return ($val >= 0 ? '+' : '') . $val . '%';
}


if (!function_exists('admin_dashboard_initials')) {
    function admin_dashboard_initials(?string $name): string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return 'NA';
        }

        $parts = preg_split('/\s+/', $name);
        $first = strtoupper(substr((string) ($parts[0] ?? ''), 0, 1));
        $last = strtoupper(substr((string) ($parts[count($parts) - 1] ?? ''), 0, 1));

        return $first . ($last ?: '');
    }
}

if (!function_exists('admin_dashboard_time_ago')) {
    function admin_dashboard_time_ago(?string $dateTime): string
    {
        if (empty($dateTime)) {
            return 'Jamais';
        }

        $timestamp = strtotime($dateTime);
        if ($timestamp === false) {
            return 'Inconnu';
        }

        $diff = time() - $timestamp;
        if ($diff < 60) {
            return 'Il y a moins d\'1 min';
        }
        if ($diff < 3600) {
            return 'Il y a ' . floor($diff / 60) . ' mins';
        }
        if ($diff < 86400) {
            return 'Il y a ' . floor($diff / 3600) . ' h';
        }

        return 'Il y a ' . floor($diff / 86400) . ' jours';
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitalPath | Admin Dashboard</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>">
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/dashboard.css') ?>">
    <style>
        .modal-backdrop.show {
            backdrop-filter: blur(10px);
            background-color: rgba(15, 23, 42, 0.45);
        }

        .user-detail-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.7rem;
            border-radius: 999px;
            background: rgba(0, 110, 47, 0.08);
            color: #0f5132;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .user-detail-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1rem;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(248, 250, 252, 0.98));
        }

        .user-detail-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            font-weight: 700;
        }

        .user-detail-value {
            color: #111827;
            font-weight: 600;
        }
    </style>

</head>

<body>

    <!-- Top Navigation Bar -->
    <header class="admin-topbar">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-heart-pulse-fill" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                    <span class="admin-brand">VitalPath</span>
                </div>

                <nav class="admin-nav d-none d-md-flex gap-2">
                    <a href="<?= site_url("admin") ?>">Dashboard</a>
                    <a href="#">Régimes</a>
                    <a href="#">Activités</a>
                </nav>

                <div class="d-flex align-items-center gap-2 gap-md-3 admin-topbar-actions">
                    <button class="btn btn-link text-secondary p-2 d-md-none admin-mobile-toggle" type="button"
                        data-bs-toggle="collapse" data-bs-target="#adminMobileNav" aria-expanded="false"
                        aria-controls="adminMobileNav" aria-label="Ouvrir la navigation">
                        <i class="bi bi-list" style="font-size: 1.45rem; color: var(--primary-green);"></i>
                    </button>
                    <div class="rounded-circle"
                        style="width: 2.5rem; height: 2.5rem; background: rgba(0, 110, 47, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-fill" style="color: var(--primary-green);"></i>
                    </div>
                </div>
            </div>

            <div class="collapse admin-mobile-nav d-md-none mt-3" id="adminMobileNav">
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= site_url("admin") ?>" class="btn btn-sm btn-outline-secondary">Dashboard</a>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Régimes</a>
                    <a href="#" class="btn btn-sm btn-outline-secondary">Activités</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-4" style="padding: 2rem 1rem; max-width: 1280px; margin: 0 auto;">

        <!-- KPI Cards -->
        <section class="row g-3 mb-4">
            <!-- Total Users -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="metric-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="metric-change"><?= formatPercent($usersChangePercent) ?></span>
                    </div>
                    <div class="metric-label">Utilisateurs Totaux</div>
                    <div class="metric-value"><?= esc(number_format($totalUsers, 0, ',', ' ')) ?></div>
                </div>
            </div>

            <!-- Active Subscriptions -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="metric-icon">
                            <i class="bi bi-card-checklist"></i>
                        </div>
                        <span class="metric-change"><?= formatPercent($subscriptionsChangePercent) ?></span>
                    </div>
                    <div class="metric-label">Abonnements Actifs</div>
                    <div class="metric-value"><?= esc(number_format($activeSubscriptions, 0, ',', ' ')) ?></div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="metric-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <span class="metric-change"><?= formatPercent($revenueChangePercent) ?></span>
                    </div>
                    <div class="metric-label">Revenu Mensuel</div>
                    <div class="metric-value"><?= esc(number_format($monthlyRevenue, 2, ',', ' ')) ?> €</div>
                </div>
            </div>

            <!-- Gold Users -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="metric-icon"
                            style="color: #b45309; background: linear-gradient(135deg, rgba(245, 158, 11, 0.22), rgba(220, 38, 38, 0.12)); border: 1px solid rgba(245, 158, 11, 0.22); box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <span class="metric-change" style="color: #b45309; background: rgba(245, 158, 11, 0.14);">
                            <?= formatPercent($goldUsersChangePercent) ?>
                        </span>
                    </div>
                    <div class="metric-label">Utilisateurs Gold</div>
                    <div class="metric-value"><?= esc(number_format($goldUsers, 0, ',', ' ')) ?></div>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="row g-4 mb-4 align-items-stretch dashboard-balanced-row">

            <!-- Chart Section -->
            <div class="col-12 col-lg-8 d-flex">
                <div class="card-section h-100 d-flex flex-column dashboard-spotlight-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="section-title m-0">Revenus vs Inscriptions</h2>
                    </div>

                    <div class="dashboard-spotlight mb-3">
                        <div class="dashboard-spotlight-copy">
                            <div class="dashboard-spotlight-kicker">Vue analytique de la semaine</div>
                            <h3>Une lecture rapide de la croissance et du rythme des inscriptions.</h3>
                            <p>Le dashboard met en avant les flux principaux avec des repères visuels plus doux pour un
                                aperçu immédiat.</p>
                        </div>
                        <div class="dashboard-spotlight-art" aria-hidden="true">
                            <span class="dashboard-spotlight-orb orb-a"></span>
                            <span class="dashboard-spotlight-orb orb-b"></span>
                            <span class="dashboard-spotlight-orb orb-c"></span>
                            <div class="dashboard-spotlight-bars">
                                <span style="height: 34%;"></span>
                                <span style="height: 58%;"></span>
                                <span style="height: 76%;"></span>
                                <span style="height: 48%;"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Weekly summary cards (replaces previous chart) -->
                    <div class="row g-2 flex-grow-1 align-content-start">
                        <?php
                        $weekRevenueTotal = array_sum($revenueData);
                        $weekInscriptionsTotal = array_sum($inscriptionData);
                        $avgPerSignup = $weekInscriptionsTotal > 0 ? $weekRevenueTotal / $weekInscriptionsTotal : 0;
                        $activeThisWeek = $thisWeekUsers ?? 0;
                        ?>
                        <div class="col-6 col-md-3">
                            <div class="metric-card small">
                                <div class="metric-label">Revenu Semaine</div>
                                <div class="metric-value"><?= esc(number_format($weekRevenueTotal, 2, ',', ' ')) ?> €
                                </div>
                                <div class="text-muted" style="font-size:0.85rem;">Total 7 derniers jours</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="metric-card small">
                                <div class="metric-label">Inscri / Sem</div>
                                <div class="metric-value"><?= esc(number_format($weekInscriptionsTotal, 0, ',', ' ')) ?>
                                </div>
                                <div class="text-muted" style="font-size:0.85rem;">Nouveaux utilisateurs</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="metric-card small">
                                <div class="metric-label">Rev / Inscri</div>
                                <div class="metric-value"><?= esc(number_format($avgPerSignup, 2, ',', ' ')) ?> €</div>
                                <div class="text-muted" style="font-size:0.85rem;">Moyenne</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="metric-card small">
                                <div class="metric-label">Actifs (sem.)</div>
                                <div class="metric-value"><?= esc(number_format($activeThisWeek, 0, ',', ' ')) ?></div>
                                <div class="text-muted" style="font-size:0.85rem;">Activés ou connectés</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Gold Member Stats & Promo Validator -->
            <div class="col-12 col-lg-4 d-flex">

                <!-- Gold Member Stats -->
                <div class="card-section h-100 d-flex flex-column gold-spotlight-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="section-title m-0" style="font-size: 1.1rem;">Statistiques Gold</h3>
                        <i class="bi bi-crown-fill"
                            style="color: #d97706; font-size: 1.25rem; filter: drop-shadow(0 6px 10px rgba(220, 38, 38, 0.14));"></i>
                    </div>


                    <div class="stats-box"
                        style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.14), rgba(220, 38, 38, 0.08)); border-color: rgba(245, 158, 11, 0.16);">
                        <strong>Taux de Rétention</strong>
                        <div class="stats-value"><?= esc($retentionRate) ?>%</div>
                        <div class="progress-bar-custom mt-2">
                            <div class="progress-fill" style="width: <?= esc(max(0, min(100, $retentionRate))) ?>%;">
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stats-box mb-0"
                                style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(255, 255, 255, 0.8)); border-color: rgba(245, 158, 11, 0.14);">
                                <strong>LTV Moyen</strong>
                                <div class="stats-value" style="font-size: 1.25rem;">
                                    <?= esc(number_format($avgLtv, 2, ',', ' ')) ?> €
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-box mb-0"
                                style="background: linear-gradient(135deg, rgba(220, 38, 38, 0.10), rgba(255, 255, 255, 0.82)); border-color: rgba(220, 38, 38, 0.12);">
                                <strong>Support 24/7</strong>
                                <div class="stats-value" style="font-size: 1.25rem;">
                                    <?= esc(number_format($supportScore, 1, ',', '')) ?>/5
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gold-spotlight-footer mt-auto pt-3">
                        <div class="gold-spotlight-footer-card">
                            <i class="bi bi-heart-pulse-fill"></i>
                            <div>
                                <strong>Suivi premium</strong>
                                <span>Ayez un rendu complet de votre application, constatez son évolution et son impact
                                    sur la société</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Recent User Activity Table -->
        <div class="card-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title m-0">Activité Utilisateur Récente</h2>
                <div class="d-flex flex-wrap gap-2 align-items-center" id="activityToolbar"
                    data-endpoint="<?= esc(site_url('admin/ajax_activities')) ?>">
                    <button type="button"
                        class="btn btn-sm <?= $activityFilter === 'all' ? 'btn-success' : 'btn-outline-secondary' ?> js-activity-filter"
                        data-activity-filter="all" data-show-all="<?= $showAllActivities ? '1' : '0' ?>"
                        style="<?= $activityFilter === 'all' ? 'background: var(--primary-green); border: none;' : '' ?>">Tous</button>
                    <button type="button"
                        class="btn btn-sm <?= $activityFilter === 'active' ? 'btn-success' : 'btn-outline-secondary' ?> js-activity-filter"
                        data-activity-filter="active" data-show-all="<?= $showAllActivities ? '1' : '0' ?>"
                        style="<?= $activityFilter === 'active' ? 'background: var(--primary-green); border: none;' : '' ?>">Actifs</button>
                    <button type="button"
                        class="btn btn-sm <?= $activityFilter === 'inactive' ? 'btn-success' : 'btn-outline-secondary' ?> js-activity-filter"
                        data-activity-filter="inactive" data-show-all="<?= $showAllActivities ? '1' : '0' ?>"
                        style="<?= $activityFilter === 'inactive' ? 'background: var(--primary-green); border: none;' : '' ?>">Inactifs</button>
                    <button type="button" class="btn btn-sm btn-success js-activity-toggle"
                        data-activity-filter="<?= esc($activityFilter) ?>"
                        data-show-all="<?= $showAllActivities ? '0' : '1' ?>"
                        style="background: var(--primary-green); border: none;"><?= $showAllActivities ? 'Voir 10' : 'Voir tous' ?></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Objectif / Plan</th>
                            <th>Statut</th>
                            <th>Dernière Connexion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activityTableBody">
                        <?= view('admin/partials/activity_table_rows', ['recentActivities' => $recentActivities]) ?>
                    </tbody>
                </table>
            </div>

            <div id="activityPager">
                <?= view('admin/partials/activity_pager', [
                    'activityFilter' => $activityFilter,
                    'showAllActivities' => $showAllActivities,
                    'activityPage' => $activityPage,
                    'activityTotalPages' => $activityTotalPages,
                    'activityTotal' => $activityTotal,
                    'activityPerPage' => $activityPerPage,
                ]) ?>
            </div>
        </div>

        <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 1.35rem; overflow: hidden;">
                    <div class="modal-header border-0 pb-0 px-4 pt-4">
                        <div>
                            <div class="text-uppercase small fw-semibold text-secondary">Fiche utilisateur</div>
                            <h5 class="modal-title mb-0" id="userDetailsModalTitle">Utilisateur</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body px-4 pb-4 pt-3">
                        <div class="row g-3 align-items-stretch">
                            <div class="col-12 col-lg-4">
                                <div class="user-detail-card h-100 p-4">
                                    <div class="d-flex align-items-center gap-3 mb-4">
                                        <div id="userDetailsAvatar"
                                            class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 4rem; height: 4rem; background: rgba(0, 110, 47, 0.12); color: var(--primary-green); font-size: 1.35rem; font-weight: 700;">
                                            --
                                        </div>
                                        <div>
                                            <div class="user-detail-label">Nom complet</div>
                                            <div id="userDetailsName" class="user-detail-value fs-5">--</div>
                                            <div id="userDetailsEmail" class="text-secondary">--</div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <span id="userDetailsStatus" class="user-detail-chip">--</span>
                                        <span id="userDetailsGold" class="user-detail-chip">--</span>
                                    </div>

                                    <div class="user-detail-card p-3 mb-3">
                                        <div class="user-detail-label mb-1">Plan / Objectif</div>
                                        <div id="userDetailsPlan" class="user-detail-value">--</div>
                                        <div id="userDetailsPlanHint" class="text-secondary small">--</div>
                                    </div>

                                    <div class="user-detail-card p-3">
                                        <div class="user-detail-label mb-1">Dernière connexion</div>
                                        <div id="userDetailsLastLogin" class="user-detail-value">--</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-8">
                                <div class="row g-3">
                                    <div class="col-6 col-md-4">
                                        <div class="user-detail-card p-3 h-100">
                                            <div class="user-detail-label">Genre</div>
                                            <div id="userDetailsGender" class="user-detail-value">--</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="user-detail-card p-3 h-100">
                                            <div class="user-detail-label">Âge</div>
                                            <div id="userDetailsAge" class="user-detail-value">--</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="user-detail-card p-3 h-100">
                                            <div class="user-detail-label">Taille</div>
                                            <div id="userDetailsHeight" class="user-detail-value">--</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="user-detail-card p-3 h-100">
                                            <div class="user-detail-label">Poids</div>
                                            <div id="userDetailsWeight" class="user-detail-value">--</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="user-detail-card p-3 h-100">
                                            <div class="user-detail-label">IMC</div>
                                            <div id="userDetailsImc" class="user-detail-value">--</div>
                                            <div id="userDetailsImcCategory" class="text-secondary small">--</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="user-detail-card p-3 h-100">
                                            <div class="user-detail-label">Portefeuille</div>
                                            <div id="userDetailsWallet" class="user-detail-value">--</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="user-detail-card p-3">
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <div class="user-detail-label">ID utilisateur</div>
                                                    <div id="userDetailsId" class="user-detail-value">--</div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="user-detail-label">Objectif enregistré</div>
                                                    <div id="userDetailsObjective" class="user-detail-value">--</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        (() => {
            const toolbar = document.getElementById('activityToolbar');
            const tableBody = document.getElementById('activityTableBody');
            const pager = document.getElementById('activityPager');
            const userModalElement = document.getElementById('userDetailsModal');
            const userModal = userModalElement ? new bootstrap.Modal(userModalElement) : null;

            if (!toolbar || !tableBody || !pager) {
                return;
            }

            const endpoint = toolbar.dataset.endpoint;

            const setModalText = (id, value) => {
                const element = document.getElementById(id);
                if (element) {
                    element.textContent = value || '--';
                }
            };

            const formatDateTime = (value) => {
                if (!value) {
                    return 'Jamais';
                }

                const normalized = value.replace(' ', 'T');
                const date = new Date(normalized);
                if (Number.isNaN(date.getTime())) {
                    return value;
                }

                return new Intl.DateTimeFormat('fr-FR', {
                    dateStyle: 'medium',
                    timeStyle: 'short'
                }).format(date);
            };

            const openUserModal = (button) => {
                if (!userModal) {
                    return;
                }

                const name = button.dataset.userName || '--';
                const email = button.dataset.userEmail || '--';
                const initials = (name.trim().split(/\s+/).filter(Boolean).slice(0, 2).map((part) => part[0] || '').join('') || 'NA').toUpperCase();
                const isActive = button.dataset.userIsActive === '1';
                const isGold = button.dataset.userIsGold === '1';
                const planLabel = button.dataset.userPlanLabel || '--';
                const planHint = button.dataset.userPlanHint || '--';
                const objectiveLabel = button.dataset.userObjectiveLabel || '--';
                const genderLabel = button.dataset.userGenderLabel || '--';

                setModalText('userDetailsModalTitle', name);
                setModalText('userDetailsAvatar', initials);
                setModalText('userDetailsName', name);
                setModalText('userDetailsEmail', email);
                setModalText('userDetailsPlan', planLabel);
                setModalText('userDetailsPlanHint', planHint);
                setModalText('userDetailsGender', genderLabel);
                setModalText('userDetailsAge', button.dataset.userAge ? `${button.dataset.userAge} ans` : '--');
                setModalText('userDetailsHeight', button.dataset.userHeight ? `${button.dataset.userHeight} cm` : '--');
                setModalText('userDetailsWeight', button.dataset.userWeight ? `${button.dataset.userWeight} kg` : '--');
                setModalText('userDetailsImc', button.dataset.userImcValue ? `${button.dataset.userImcValue} kg/m²` : '--');
                setModalText('userDetailsImcCategory', button.dataset.userImcCategory || '--');
                setModalText('userDetailsWallet', button.dataset.userWalletBalance ? `${button.dataset.userWalletBalance} €` : '0,00 €');
                setModalText('userDetailsObjective', objectiveLabel);
                setModalText('userDetailsId', button.dataset.userId || '--');
                setModalText('userDetailsCreatedAt', formatDateTime(button.dataset.userCreatedAt || ''));
                setModalText('userDetailsUpdatedAt', formatDateTime(button.dataset.userUpdatedAt || ''));
                setModalText('userDetailsLastLogin', formatDateTime(button.dataset.userLastLogin || ''));

                const statusEl = document.getElementById('userDetailsStatus');
                if (statusEl) {
                    statusEl.textContent = isActive ? 'Compte actif' : 'Compte inactif';
                    statusEl.style.background = isActive ? 'rgba(0, 110, 47, 0.10)' : 'rgba(107, 114, 128, 0.10)';
                    statusEl.style.color = isActive ? '#0f5132' : '#4b5563';
                }

                const goldEl = document.getElementById('userDetailsGold');
                if (goldEl) {
                    goldEl.textContent = isGold ? 'Membre Gold' : 'Non Gold';
                    goldEl.style.background = isGold ? 'rgba(245, 158, 11, 0.14)' : 'rgba(107, 114, 128, 0.10)';
                    goldEl.style.color = isGold ? '#92400e' : '#4b5563';
                }

                userModal.show();
            };

            const loadActivities = (params) => {
                const url = new URL(endpoint, window.location.origin);
                Object.entries(params).forEach(([key, value]) => {
                    url.searchParams.set(key, value);
                });

                tableBody.classList.add('opacity-75');

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data.success) {
                            return;
                        }

                        tableBody.innerHTML = data.rowsHtml;
                        pager.innerHTML = data.pagerHtml;

                        tableBody.classList.remove('opacity-75');
                        bindPagerActions();
                    })
                    .catch(() => {
                        tableBody.classList.remove('opacity-75');
                    });
            };

            const bindPagerActions = () => {
                pager.querySelectorAll('.js-activity-page').forEach((button) => {
                    if (button.disabled || !button.dataset.page) {
                        return;
                    }

                    button.addEventListener('click', () => {
                        loadActivities({
                            activity_filter: getCurrentFilter(),
                            show_all: getCurrentShowAll(),
                            page: button.dataset.page
                        });
                    });
                });
            };

            const getCurrentFilter = () => {
                const activeFilter = toolbar.querySelector('.js-activity-filter.btn-success') || toolbar.querySelector('.js-activity-toggle.btn-success');
                return activeFilter ? (activeFilter.dataset.activityFilter || 'all') : 'all';
            };

            const getCurrentShowAll = () => {
                const toggle = toolbar.querySelector('.js-activity-toggle');
                return toggle ? (toggle.dataset.showAll || '0') : '0';
            };

            const setActiveButton = (filter, showAll) => {
                toolbar.querySelectorAll('.js-activity-filter').forEach((button) => {
                    const isActive = button.dataset.activityFilter === filter;
                    button.classList.toggle('btn-success', isActive);
                    button.classList.toggle('btn-outline-secondary', !isActive);
                    button.style.background = isActive ? 'var(--primary-green)' : '';
                    button.style.border = isActive ? 'none' : '';
                    button.dataset.showAll = showAll;
                });

                const toggle = toolbar.querySelector('.js-activity-toggle');
                if (toggle) {
                    toggle.dataset.activityFilter = filter;
                    toggle.dataset.showAll = showAll;
                    toggle.textContent = showAll === '1' ? 'Voir 10' : 'Voir tous';
                }
            };

            toolbar.querySelectorAll('.js-activity-filter').forEach((button) => {
                button.addEventListener('click', () => {
                    const filter = button.dataset.activityFilter || 'all';
                    const showAll = button.dataset.showAll || '0';
                    setActiveButton(filter, showAll);
                    loadActivities({
                        activity_filter: filter,
                        show_all: showAll,
                        page: 1
                    });
                });
            });

            const toggle = toolbar.querySelector('.js-activity-toggle');
            if (toggle) {
                toggle.addEventListener('click', () => {
                    const filter = toggle.dataset.activityFilter || 'all';
                    const showAll = toggle.dataset.showAll || '0';
                    const nextShowAll = showAll === '1' ? '0' : '1';
                    toggle.dataset.showAll = nextShowAll;
                    toggle.textContent = nextShowAll === '1' ? 'Voir 10' : 'Voir tous';
                    loadActivities({
                        activity_filter: filter,
                        show_all: nextShowAll,
                        page: 1
                    });
                });
            }

            tableBody.addEventListener('click', (event) => {
                const trigger = event.target.closest('.js-open-user-modal');
                if (!trigger) {
                    return;
                }

                event.preventDefault();
                openUserModal(trigger);
            });

            bindPagerActions();
        })();
    </script>

</body>

</html>