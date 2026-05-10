<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VitalPath | Catalogue regimes</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>" />
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/wallet.css') ?>">

    <style>
        .regime-grid-card {
            border: 1px solid rgba(188, 203, 185, 0.32);
            border-radius: 1rem;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
            background: #fff;
            height: 100%;
        }

        .regime-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-green);
        }

        .regime-badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .regime-badge-gold {
            background: #ffe08a;
            color: #6f4f00;
        }

        .regime-badge-owned {
            background: #d1fae5;
            color: #065f46;
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
                    <a href="<?= site_url('dashboard') ?>">Tableau de bord</a>
                    <a class="active" href="<?= site_url('regimes') ?>">Regimes</a>
                    <a href="<?= site_url('wallet') ?>">Portefeuille</a>
                </nav>

                <div class="d-flex align-items-center gap-2 gap-md-3 admin-topbar-actions">
                    <div class="rounded-circle"
                        style="width: 2.5rem; height: 2.5rem; background: rgba(0, 110, 47, 0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-fill" style="color: var(--primary-green);"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="px-3 px-md-4 py-4 py-md-5">
        <div class="container-fluid" style="max-width: 1180px;">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="section-title mb-2">Catalogue des regimes</h1>
                    <p class="text-secondary mb-0">Choisissez un regime et achetez-le avec votre portefeuille.</p>
                </div>
                <div class="text-end">
                    <div class="wallet-summary-label">Solde disponible</div>
                    <div class="wallet-summary-value green">€<?= number_format((float) ($walletBalance ?? 0), 2) ?></div>
                </div>
            </div>

            <div class="row g-4">
                <?php foreach (($regimes ?? []) as $regime): ?>
                    <?php
                    $basePrice = (float) ($regime['price_per_week'] ?? 0) * (int) ($regime['duration_weeks'] ?? 1);
                    $finalPrice = (bool) ($isGold ?? false) ? ($basePrice * 0.85) : $basePrice;
                    $isPurchased = in_array((int) ($regime['id'] ?? 0), $purchasedRegimeIds ?? [], true);
                    $canAfford = (float) ($walletBalance ?? 0) >= $finalPrice;
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="regime-grid-card p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h3 class="h5 mb-0"><?= esc($regime['name'] ?? 'Regime') ?></h3>
                                <?php if ($isPurchased): ?>
                                    <span class="regime-badge regime-badge-owned">Deja achete</span>
                                <?php endif; ?>
                            </div>

                            <p class="text-secondary small mb-3"><?= esc($regime['description'] ?? 'Description non disponible.') ?></p>

                            <div class="mb-2">
                                <span class="regime-price">€<?= number_format($finalPrice, 2) ?></span>
                                <?php if ((bool) ($isGold ?? false)): ?>
                                    <span class="text-secondary ms-2" style="text-decoration: line-through;">€<?= number_format($basePrice, 2) ?></span>
                                    <span class="regime-badge regime-badge-gold ms-2">Gold -15%</span>
                                <?php endif; ?>
                            </div>

                            <div class="small text-secondary mb-4">
                                <div>Duree: <?= (int) ($regime['duration_weeks'] ?? 0) ?> semaines</div>
                                <div>Cible calories: <?= (int) ($regime['calorie_target'] ?? 0) ?> kcal/jour</div>
                            </div>

                            <?php if ($isPurchased): ?>
                                <button type="button" class="btn btn-outline-secondary rounded-pill mt-auto" disabled>Deja dans vos achats</button>
                            <?php elseif (! $canAfford): ?>
                                <button type="button" class="btn btn-outline-danger rounded-pill mt-auto" disabled>Solde insuffisant</button>
                            <?php else: ?>
                                <form method="post" action="<?= site_url('regime/' . (int) ($regime['id'] ?? 0) . '/purchase') ?>" class="mt-auto">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-success rounded-pill w-100">Acheter ce regime</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>
