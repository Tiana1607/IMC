<?php
$currentPath = strtolower(trim(service('uri')->getPath(), '/'));

$navActive = static function (string $needle) use ($currentPath): string {
    if ($needle === 'dashboard') {
        return ($currentPath === '' || strpos($currentPath, 'dashboard') === 0 || strpos($currentPath, 'admin') === 0) ? 'active' : '';
    }

    return strpos($currentPath, $needle) !== false ? 'active' : '';
};

if (! isset($currentPlan)) {
    $currentPlan = 'Standard';
}

$metrics = $metrics ?? [
    ['label' => 'IMC actuel', 'value' => '23.4', 'hint' => 'Santé stable', 'icon' => 'activity', 'tone' => 'green'],
    ['label' => 'Poids', 'value' => '74 kg', 'hint' => '-1,2 kg depuis la semaine dernière', 'icon' => 'person-standing', 'tone' => 'neutral'],
    ['label' => 'Taille', 'value' => '178 cm', 'hint' => 'Mesure stable', 'icon' => 'rulers', 'tone' => 'neutral'],
];

$diets = $diets ?? [
    [
        'title' => 'Équilibre méditerranéen',
        'description' => 'Met l’accent sur les bonnes graisses et une alimentation végétale pour le cœur et le cerveau.',
        'badge' => 'Vitalité',
        'image' => base_url('assets/images/image2.png'),
    ],
    [
        'title' => 'Apport protéiné',
        'description' => 'Idéal pour la récupération musculaire et la force métabolique.',
        'badge' => 'Récupération',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCombebEqT0bUVQFZdQF2jmJj8rDDDmu8pCSLzmti9-5fGUcc3sd6EjryfgKzuYHOzz2wbNeHzTCQr2pQFwR9pUpfGPygabBzu718KkVGCV_goPMgOK2iof7_bfj8892F5V7jacSwM9DsCWSeAM09yAj3ukqTzYXJPH0c0gAXGfFlox8tIcXJB44bXj01-yzKDye9UtECV8CZH1t58yjXsoWMPyjXpoCO1vg8LmkArUX2ZPiXKaSq6hSuIoD7l0QvfNTeleaiTVDkMx',
    ],
    [
        'title' => 'Détox végétale',
        'description' => 'Cure intensive de 7 jours riche en fibres et micronutriments.',
        'badge' => 'Réinitialiser',
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA1WBPPkwhNrQT7hufvbQYy3vUdjoWZiPTMGF5R6MNxRZEpnLJUKPMvWPaSXlzTn0GTRzWMpGtHU7WsMdLSUFZJIvQHM0i12tMiB8wbw08uRMsftERv4AIJrpZQVHcsxzqdhzN3_Ea-WSosplDupbBmdmJLcvAWVTAS7vlvOcKy2d5C70Xw9-JuUTAvgDpZtGKH4PovuIcBbYfvns7mXWsAK0e0mpX61asylfvhRPKvyaWSGYKWYMvUks-qCUu47IGZKwvZfeOMd7nA',
    ],
];

$activities = $activities ?? [
    ['title' => 'Yoga du matin', 'time' => '15 MIN', 'kcal' => 120, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCF8wQjrukfWWJYR4S9NgNFYGw8WvF6e5Ye6OyoXEM1lK9HoBCRGk3ZQRdx3MGg4q0Pai59TbwOxCfgI4Uuulcn3gT6wzGGF2X-GogrxxRBykE1dJXI29-J5W3C77gX0jnAgkvn572TdosDGbH1AqYBQmoGkS2JnsTRPD3SzvVhMSWmgi0QvbG4a96-acVZ9EwEdAYM5xsThVqw3MXNWN4_y0IuMR8BkNwcq-s_EKr1WA93YMh28AgjTDlpaxA_zxgz5q6fXnL-p6wJ'],
    ['title' => 'Natation', 'time' => '30 MIN', 'kcal' => 350, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA-4fa5E4vonRSRCkeYViL-h2yGzyiJ7BVkOAA6L0Sw7dtOOuwpErVMRwTA_IbYDbhM9-H9_T1bsrCZcf0ku5Kami6YyDoftFbb-Zuhz0SKEYjlXJVykNcfA38hvpIQPMwi-2HeonOR9aOoCtcbQGf0CwNunwTv-K-X5hj6BcBrFfou4pc6o-mkBsO-puyv58FzjXBel_Hwdl9yKd9_y1HhlWyUNv3erDdNRA232sRywIwTROcwRebODCHYEgtWOcwahNOEeR2wbbym'],
    ['title' => 'Renforcement du core', 'time' => '20 MIN', 'kcal' => 180, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBqGm6qL8Nn7g7c5D1C5X1K3mB3Yk3M6p1U0wQy2l6J4QwqR2m9S1h6g7a8c9d0e1f2g3h4i5j6k7l8m9n0oPqRStU2vXyZ1a2b3c4d5e6f7g8h9i0j'],
    ['title' => 'Marche du soir', 'time' => '45 MIN', 'kcal' => 220, 'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBrm5n3xUu3jV8b9Q0x1A2b3C4d5E6f7G8h9I0j1K2l3m4N5o6P7q8R9s0T1u2V3w4X5y6z7A8b9C0d1E2f3G4h5I6j7K8l9M0n1O2p3q4r5s6t7u8v9w0x'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VitalPath | Tableau de bord</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>" />
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/wallet.css') ?>">
    <style>
        .user-dashboard-hero {
            min-height: 220px;
        }

        .user-dashboard-card {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(188, 203, 185, 0.32);
            border-radius: 1.25rem;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }

        .user-dashboard-cover {
            min-height: 200px;
            border-radius: 1.25rem;
            overflow: hidden;
            position: relative;
        }

        .user-dashboard-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }

        .user-dashboard-cover:hover img {
            transform: scale(1.03);
        }

        .user-dashboard-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.08), rgba(0, 0, 0, 0.72));
        }

        .user-dashboard-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            background: rgba(164, 241, 178, 0.92);
            color: #135a2b;
        }

        .user-dashboard-bottom-nav {
            background: rgba(247, 249, 251, 0.95);
            backdrop-filter: blur(14px);
            border-top: 1px solid rgba(109, 123, 108, 0.18);
            box-shadow: 0 -4px 18px rgba(17, 24, 39, 0.04);
        }

        .user-dashboard-bottom-link {
            color: var(--muted);
            border-radius: 999px;
            padding: 0.45rem 0.6rem;
            min-width: 4rem;
            text-decoration: none;
        }

        .user-dashboard-bottom-link.active,
        .user-dashboard-bottom-link:hover,
        .user-dashboard-bottom-link:focus-visible {
            color: var(--primary-green);
            background: rgba(0, 110, 47, 0.08);
        }

        @media (min-width: 768px) {
            .user-dashboard-main {
                margin-left: 16rem;
            }
        }

        @media (max-width: 767.98px) {
            .user-dashboard-main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <header class="admin-topbar">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-heart-pulse-fill" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                    <span class="admin-brand">VitalPath</span>
                </div>

                <nav class="admin-nav d-none d-md-flex gap-2">
                    <a class="<?= $navActive('dashboard') ?>" href="<?= site_url('/dashboard') ?>">Tableau de bord</a>
                    <a class="<?= $navActive('regimes') ?>" href="<?= site_url('regimes') ?>">Régimes</a>
                    <!-- Activités (À venir) -->
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
                    <a href="<?= site_url('/dashboard') ?>" class="btn btn-sm btn-outline-secondary">Tableau de bord</a>
                    <a href="<?= site_url('regimes') ?>" class="btn btn-sm btn-outline-secondary">Régimes</a>
                    <a href="<?= site_url('/wallet') ?>" class="btn btn-sm btn-outline-secondary">Portefeuille</a>
                    <a href="<?= site_url('auth/logout') ?>" class="btn btn-sm btn-outline-secondary">Déconnexion</a>
                </div>
            </div>
        </div>
    </header>

    <div class="user-dashboard-shell" style="width: 100%;">
        <aside class="hidden d-none d-md-flex flex-column position-fixed top-0 bottom-0 start-0 wallet-sidebar" style="width: 16rem; z-index: 900; left: 0;">
            <div class="pt-5 px-3" style="padding-top: 5.2rem !important;">
                <div class="d-grid gap-2">
                    <a class="wallet-sidebar-link <?= $navActive('dashboard') ?>" href="<?= site_url() ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a class="wallet-sidebar-link <?= $navActive('wallet') ?>" href="<?= site_url('wallet') ?>">
                        <i class="bi bi-wallet2"></i>
                        <span>Portefeuille</span>
                    </a>
                    <a class="wallet-sidebar-link <?= $navActive('profile') ?>" href="<?= site_url('profile/view') ?>">
                        <i class="bi bi-person-circle"></i>
                        <span>Mon profil</span>
                    </a>
                    <a class="wallet-sidebar-link" href="<?= site_url('auth/logout') ?>">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Déconnexion</span>
                    </a>
                </div>

                <div class="wallet-plan-box mt-4 p-3">
                    <p class="wallet-plan-label mb-2">Forfait actuel</p>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="wallet-plan-name"><?= esc($currentPlan) ?></span>
                    </div>
                    <?php if (!empty($isGold)): ?>
                        <button type="button" class="btn btn-success w-100 rounded-pill fw-semibold" disabled>Membre Gold actif</button>
                    <?php else: ?>
                        <?php 
                            $goldPrice = $goldPrice ?? 49.0;
                            $balance = $walletBalance ?? 0;
                            $canUpgrade = $balance >= $goldPrice;
                            $missing = max(0, $goldPrice - $balance);
                        ?>
                        <form method="post" action="<?= site_url('upgrade-gold') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" 
                                    class="btn <?= $canUpgrade ? 'btn-success' : 'btn-secondary' ?> w-100 rounded-pill fw-semibold" 
                                    <?= !$canUpgrade ? 'disabled' : '' ?>>
                                <?= $canUpgrade ? 'Devenir Gold ⭐' : 'Solde insuffisant (-€' . number_format($missing, 2) . ')' ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <main class="user-dashboard-main flex-grow-1 px-3 px-md-4 py-4 py-md-5">
            <div class="container-fluid px-0" style="max-width: 1180px;">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div>
                        <h1 class="section-title mb-2" style="font-size: clamp(1.45rem, 2vw, 2rem);">Aperçu du jour</h1>
                        <p class="text-secondary mb-0">Vos progrès sont très bons aujourd’hui, continuez comme ça.</p>
                    </div>
                    <span class="user-dashboard-pill">Tableau bien-être</span>
                </div>

                <section class="row g-3 mb-4">
                    <?php foreach ($metrics as $metric): ?>
                        <div class="col-12 col-md-4">
                            <div class="metric-card user-dashboard-card h-100 text-center">
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="metric-icon" style="background: <?= $metric['tone'] === 'green' ? 'rgba(164, 241, 178, 0.34)' : 'rgba(230, 232, 234, 0.9)' ?>;">
                                        <i class="bi bi-<?= esc($metric['icon']) ?>"></i>
                                    </div>
                                </div>
                                <div class="metric-label"><?= esc($metric['label']) ?></div>
                                <div class="metric-value"><?= esc($metric['value']) ?></div>
                                <div class="metric-note"><?= esc($metric['hint']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-8">
                        <div class="card-section h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="section-title m-0">Régimes recommandés</h2>
                                <a href="<?= site_url('regimes') ?>" class="wallet-view-all">Voir tout</a>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="user-dashboard-cover">
                                        <img src="<?= esc($diets[0]['image']) ?>" alt="<?= esc($diets[0]['title']) ?>">
                                        <div class="user-dashboard-overlay"></div>
                                        <div class="position-absolute bottom-0 start-0 p-4 text-white w-100">
                                            <span class="user-dashboard-pill mb-2"><?= esc($diets[0]['badge']) ?></span>
                                            <h3 class="mb-2" style="font-size: clamp(1.2rem, 2vw, 1.75rem); font-weight: 700;"> <?= esc($diets[0]['title']) ?></h3>
                                            <p class="mb-3 text-white-75" style="max-width: 46rem;"><?= esc($diets[0]['description']) ?></p>
                                            <a href="<?= site_url('regimes') ?>" class="btn btn-light rounded-pill fw-semibold">Découvrir ce régime</a>
                                        </div>
                                    </div>
                                </div>

                                <?php foreach (array_slice($diets, 1) as $diet): ?>
                                    <div class="col-12 col-md-6">
                                        <div class="user-dashboard-card p-3 h-100 d-flex gap-3 align-items-center">
                                            <div class="flex-shrink-0 rounded-4 overflow-hidden" style="width: 5rem; height: 5rem;">
                                                <img src="<?= esc($diet['image']) ?>" alt="<?= esc($diet['title']) ?>" class="w-100 h-100 object-fit-cover">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="wallet-pill wallet-pill-soft mb-2"><?= esc($diet['badge']) ?></div>
                                                <h4 class="mb-1" style="font-size: 1rem; font-weight: 700;"><?= esc($diet['title']) ?></h4>
                                                <p class="text-secondary mb-0 small"><?= esc($diet['description']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card-section h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="section-title m-0">Activités suggérées</h2>
                                <i class="bi bi-heart-pulse" style="color: var(--primary-green);"></i>
                            </div>
                            <div class="d-grid gap-3">
                                <?php foreach ($activities as $activity): ?>
                                    <div class="user-dashboard-card p-3 d-flex gap-3 align-items-center">
                                        <div class="rounded-4 overflow-hidden flex-shrink-0" style="width: 4.5rem; height: 4.5rem;">
                                            <img src="<?= esc($activity['image']) ?>" alt="<?= esc($activity['title']) ?>" class="w-100 h-100 object-fit-cover">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <h3 class="mb-1" style="font-size: .98rem; font-weight: 700;"><?= esc($activity['title']) ?></h3>
                                                    <div class="text-secondary small"><?= esc($activity['kcal']) ?> kcal</div>
                                                </div>
                                                <span class="badge rounded-pill text-bg-light border"><?= esc($activity['time']) ?></span>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Recommandations Vue (Régimes + Activités) -->
    <div class="recommendations-wrapper" style="background-color: #f8f9fa; padding: 2rem 1rem;">
        <div class="container-fluid px-0" style="max-width: 1180px; margin: 0 auto;">
            <?= view('recommendations', [
                'diets' => $diets ?? [],
                'activities' => $activities ?? [],
                'imc' => $imc ?? null,
                'imcCategory' => $imcCategory ?? null,
                'objectiveLabel' => $objectiveLabel ?? 'Non défini',
            ]) ?>
        </div>
    </div>

    <nav class="navbar fixed-bottom d-md-none user-dashboard-bottom-nav px-3 py-2" style="font-size: 0.75rem">
        <div class="container-fluid px-0 justify-content-around">
            <a class="user-dashboard-bottom-link active d-flex flex-column align-items-center justify-content-center text-center" href="<?= site_url() ?>" aria-current="page">
                <i class="bi bi-speedometer2"></i>
                <span class="small text-uppercase mt-1 fw-semibold">Accueil</span>
            </a>
            <a class="user-dashboard-bottom-link d-flex flex-column align-items-center justify-content-center text-center" href="#">
                <i class="bi bi-activity"></i>
                <span class="small text-uppercase mt-1 fw-semibold">Mesures</span>
            </a>
            <a class="user-dashboard-bottom-link d-flex flex-column align-items-center justify-content-center text-center" href="#">
                <i class="bi bi-bullseye"></i>
                <span class="small text-uppercase mt-1 fw-semibold">Objectifs</span>
            </a>
            <a class="user-dashboard-bottom-link d-flex flex-column align-items-center justify-content-center text-center" href="<?= site_url('wallet') ?>">
                <i class="bi bi-wallet2"></i>
                <span class="small text-uppercase mt-1 fw-semibold">Portefeuille</span>
            </a>
        </div>
    </nav>

    <!-- Promo modal -->
    <div class="modal fade" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promoModalLabel">Saisir le code promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary mb-3">Entrez votre code promo pour créditer votre portefeuille instantanément.</p>
                    <form method="post" action="<?= site_url('wallet/add-code') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="promo_code_modal" class="form-label">Code promo</label>
                            <input type="text" name="promo_code" id="promo_code_modal" class="form-control" placeholder="VITAL-PATH-2024">
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-success">Appliquer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
