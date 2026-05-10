<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>VitalPath | Portefeuille</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>" />
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/wallet.css') ?>">

</head>

<body>
    <div class="wallet-page">
        <header class="admin-topbar">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-heart-pulse-fill" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                        <span class="admin-brand">VitalPath</span>
                    </div>

                    <nav class="admin-nav d-none d-md-flex gap-2">
                        <a href="#">Tableau de bord</a>
                        <a href="<?= site_url('regimes') ?>">Régimes</a>
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
                        <a class="btn btn-sm btn-outline-secondary" href="#">Tableau de bord</a>
                        <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('regimes') ?>">Régimes</a>
                        <a class="btn btn-sm btn-outline-secondary" href="#">Activités</a>
                        <a class="btn btn-sm btn-outline-secondary" href="#">Portefeuille</a>
                        <a class="btn btn-sm btn-outline-secondary"
                            href="<?= site_url('auth/logout') ?>">Deconnexion</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="walletMobileMenu"
            aria-labelledby="walletMobileMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="walletMobileMenuLabel">VitalPath</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="d-grid gap-2">
                    <a class="btn btn-outline-success rounded-pill text-start" href="#">Tableau de bord</a>
                    <a class="btn btn-outline-success rounded-pill text-start" href="#">Régimes</a>
                    <a class="btn btn-outline-success rounded-pill text-start" href="#">Activités</a>
                    <a class="btn btn-outline-success rounded-pill text-start" href="#">Portefeuille</a>
                    <a class="btn btn-outline-success rounded-pill text-start" href="<?= site_url('auth/logout') ?>">Deconnexion</a>
                </div>
            </div>
        </div> -->

        <div class="wallet-shell d-flex">
            <aside class="wallet-sidebar d-none d-md-flex flex-column position-fixed top-0 bottom-0">
                <div class="pt-5 px-3" style="padding-top: 5.2rem !important;">
                        <?php
                        $uri = service('uri');
                        $currentPath = strtolower(trim($uri->getPath(), '/'));
                        ?>
                    <div class="d-grid gap-2">
                            <a class="wallet-sidebar-link <?= ($currentPath === '' || strpos($currentPath, 'admin') === 0) ? 'active' : '' ?>" href="<?= site_url() ?>">
                                <i class="bi bi-speedometer2"></i>
                                <span>Tableau de bord</span>
                            </a>
                        <!-- <a class="wallet-sidebar-link" href="#">
                            <i class="bi bi-basket"></i>
                            <span>Régime</span>
                        </a>
                        <a class="wallet-sidebar-link" href="#">
                            <i class="bi bi-heart-pulse"></i>
                            <span>Activités</span>
                        </a> -->
                        <a class="wallet-sidebar-link <?= strpos($currentPath, 'wallet') !== false ? 'active' : '' ?>" href="<?= site_url('wallet') ?>" aria-current="page">
                            <i class="bi bi-wallet2"></i>
                            <span>Portefeuille</span>
                        </a>
                        <a class="wallet-sidebar-link" href="<?= site_url('auth/logout') ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Deconnexion</span>
                        </a>
                    </div>

                    <div class="wallet-plan-box mt-4 p-3">
                        <p class="wallet-plan-label mb-2">Forfait actuel</p>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="wallet-plan-name"><?= esc($currentPlan ?? 'Standard') ?></span>
                        </div>
                        <?php if (!empty($isGold)): ?>
                            <button type="button" class="btn btn-success w-100 rounded-pill fw-semibold" disabled>Membre Gold actif</button>
                        <?php else: ?>
                            <form method="post" action="<?= site_url('upgrade-gold') ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success w-100 rounded-pill fw-semibold">Devenir Gold</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>

            <main class="wallet-main flex-grow-1 ms-md-auto px-3 px-md-4 py-4 py-md-5" style="margin-left: 0;">
                <div class="container-fluid px-0" style="max-width: 1180px;">
                    <div class="row g-4">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="col-12">
                                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="col-12">
                                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                            </div>
                        <?php endif; ?>
                        <div class="col-12 col-lg-8 d-flex">
                            <div
                                class="wallet-card wallet-balance-card w-100 p-4 p-md-5 d-flex flex-column justify-content-between">
                                <div class="wallet-card-content">
                                    <div class="wallet-balance-label mb-2">Solde disponible</div>
                                    <div class="wallet-balance-value">€<?= number_format($balance ?? 0, 2) ?></div>
                                </div>

                                <div class="wallet-card-content wallet-balance-actions d-flex gap-3 flex-wrap">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 d-flex">
                            <div
                                class="wallet-card wallet-upgrade-card w-100 p-4 p-md-5 d-flex flex-column justify-content-between">
                                <div class="wallet-card-content">
                                    <span class="wallet-pill wallet-pill-soft mb-4"><?= esc($offer['label'] ?? 'Offre limitée') ?></span>
                                    <div class="wallet-upgrade-copy">
                                        <h3 class="mb-2"><?= esc($offer['name'] ?? 'Pass Gold') ?></h3>
                                        <p><?= esc($offer['description'] ?? '') ?></p>
                                    </div>
                                </div>

                                <div class="wallet-card-content mt-4 mt-md-0">
                                    <div class="d-flex align-items-end gap-3 mb-4 flex-wrap">
                                        <div class="wallet-offer-percent"><?= (int) ($offer['discount_percent'] ?? 15) ?>%<br>OFF</div>
                                        <div class="text-white-50 pb-1">sur chaque achat</div>
                                    </div>
                                    <?php if (!empty($isGold)): ?>
                                        <button type="button" class="btn wallet-btn wallet-btn-gold w-100 rounded-4 py-3" disabled>Membre Gold actif</button>
                                    <?php else: ?>
                                        <form method="post" action="<?= site_url('upgrade-gold') ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn wallet-btn wallet-btn-gold w-100 rounded-4 py-3"><?= esc($offer['cta_text'] ?? 'Être un membre Gold') ?> </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mt-0 mt-md-1">
                        <div class="col-12 col-md-6 d-flex">
                            <div
                                class="wallet-card wallet-promo-card w-100 p-4 p-md-4 d-flex flex-column justify-content-center">
                                <h4 class="wallet-section-title mb-2 d-flex align-items-center gap-2">
                                    <i class="bi bi-gift"></i>
                                    <span><?= esc($promoCardTitle ?? 'Utiliser un code promo') ?></span>
                                </h4>
                                <p class="text-secondary mb-3"><?= esc($promoCardDescription ?? 'Entrez votre code ci-dessous pour ajouter des crédits instantanément à votre compte.') ?></p>
                                <form method="post" action="<?= site_url('wallet/add-code') ?>">
                                    <?= csrf_field() ?>
                                    <div class="input-group wallet-input-group wallet-promo-row">
                                        <input type="text" name="promo_code" class="form-control"
                                            placeholder="VITAL-PATH-2024" aria-label="Promo code">
                                        <button type="submit" class="btn wallet-apply-btn">Appliquer</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 d-flex">
                            <div
                                class="wallet-card wallet-summary-card w-100 p-4 d-flex align-items-center justify-content-around gap-3 flex-wrap">
                                <div class="text-center">
                                    <div class="wallet-summary-label mb-2">Dépensé ce mois</div>
                                    <div class="wallet-summary-value red">€<?= number_format($monthlySpent ?? 0, 2) ?></div>
                                </div>
                                <div class="wallet-divider d-none d-md-block"></div>
                                <div class="text-center">
                                    <div class="wallet-summary-label mb-2">Économies</div>
                                    <div class="wallet-summary-value green">€<?= number_format($monthlySavings ?? 0, 2) ?></div>
                                </div>
                                <div class="wallet-divider d-none d-md-block"></div>
                                <div class="text-center">
                                    <div class="wallet-summary-label mb-2">Récompenses actives</div>
                                    <div class="wallet-summary-value dark"><?= (int) ($activeRewards ?? 0) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <section class="mt-4 mt-md-5" id="transactions">
                        <div class="d-flex align-items-center justify-content-between mb-3 wallet-table-header">
                            <h3 class="wallet-section-title mb-0">Historique des transactions</h3>
                            <a href="<?= site_url('wallet') ?>#transactions" class="wallet-view-all">Voir tout (<?= (int) ($totalTransactions ?? 0) ?>)</a>
                        </div>

                        <div class="wallet-card wallet-table-card p-2 p-md-3 d-none d-md-block">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0 wallet-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Transaction</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($transactions) && is_array($transactions)): ?>
                                            <?php foreach ($transactions as $t): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <?php if ($t['type'] === 'credit'): ?>
                                                                <span class="wallet-transaction-icon green">
                                                                    <i class="bi bi-credit-card"></i>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="wallet-transaction-icon red">
                                                                    <i class="bi bi-basket"></i>
                                                                </span>
                                                            <?php endif; ?>
                                                            <div>
                                                                <div class="wallet-transaction-title">
                                                                    <?= esc($t['description']) ?></div>
                                                                <div class="wallet-transaction-subtitle">
                                                                    <?= $t['type'] === 'credit' ? 'Crédit' : 'Achat' ?></div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-secondary">
                                                        <?= $t['date'] ? date('d M, Y', strtotime($t['date'])) : '' ?></td>
                                                    <td><span
                                                            class="wallet-status"><?= $t['status'] === 'active' ? 'Actif' : 'Terminé' ?></span>
                                                    </td>
                                                    <td
                                                        class="text-end wallet-amount <?= $t['amount'] > 0 ? 'text-success' : '' ?>">
                                                        <?= ($t['amount'] > 0 ? '+' : '') . '€' . number_format(abs($t['amount']), 2) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-secondary">Aucune transaction
                                                    disponible.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="wallet-activity-list d-md-none">
                            <?php if (!empty($transactions) && is_array($transactions)): ?>
                                <?php foreach ($transactions as $t): ?>
                                    <div class="wallet-activity-card">
                                        <div class="d-flex align-items-flex-start gap-3 mb-2">
                                            <?php if ($t['type'] === 'credit'): ?>
                                                <span class="wallet-transaction-icon green flex-shrink-0">
                                                    <i class="bi bi-credit-card"></i>
                                                </span>
                                            <?php else: ?>
                                                <span class="wallet-transaction-icon red flex-shrink-0">
                                                    <i class="bi bi-basket"></i>
                                                </span>
                                            <?php endif; ?>
                                            <div class="flex-grow-1">
                                                <div class="wallet-transaction-title"><?= esc($t['description']) ?></div>
                                                <div class="wallet-transaction-subtitle">
                                                    <?= $t['type'] === 'credit' ? 'Récompense promo' : 'Achat' ?></div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-baseline justify-content-between gap-2 mb-2">
                                            <span
                                                class="wallet-amount <?= $t['amount'] > 0 ? 'text-success fw-bold' : 'text-danger fw-bold' ?>"><?= ($t['amount'] > 0 ? '+' : '-') . '€' . number_format(abs($t['amount']), 2) ?></span>
                                            <span
                                                class="wallet-status"><?= $t['status'] === 'active' ? 'Actif' : 'Terminé' ?></span>
                                        </div>
                                        <div class="text-secondary small">
                                            <?= $t['date'] ? date('d M, Y', strtotime($t['date'])) : '' ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-secondary">Aucune transaction disponible.</div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </main>
        </div>

        <nav class="navbar fixed-bottom d-md-none wallet-bottom-nav px-3 py-2" style="font-size: 0.75rem;">
            <div class="container-fluid px-0 justify-content-around">
                <a class="wallet-mobile-link d-flex flex-column align-items-center justify-content-center text-center"
                    href="#">
                    <i class="bi bi-basket"></i>
                    <span class="small text-uppercase mt-1 fw-semibold">Plan</span>
                </a>
                <a class="wallet-mobile-link d-flex flex-column align-items-center justify-content-center text-center"
                    href="#">
                    <i class="bi bi-bar-chart"></i>
                    <span class="small text-uppercase mt-1 fw-semibold">Metrics</span>
                </a>
                <a class="wallet-mobile-link d-flex flex-column align-items-center justify-content-center text-center"
                    href="#">
                    <i class="bi bi-flag"></i>
                    <span class="small text-uppercase mt-1 fw-semibold">Goals</span>
                </a>
                <a class="wallet-mobile-link active d-flex flex-column align-items-center justify-content-center text-center"
                    href="#" aria-current="page">
                    <i class="bi bi-wallet2"></i>
                    <span class="small text-uppercase mt-1 fw-semibold">Portefeuille</span>
                </a>
            </div>
        </nav>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- Promo modal -->
    <div class="modal fade" id="promoModal" tabindex="-1" aria-labelledby="promoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promoModalLabel">Saisir le code promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="<?= site_url('wallet/add-code') ?>">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="promo_code_modal" class="form-label">Code promo</label>
                            <input type="text" name="promo_code" id="promo_code_modal" class="form-control"
                                placeholder="VITAL-PATH-2024">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success">Appliquer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>