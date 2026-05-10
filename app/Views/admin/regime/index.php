<?php
$adminRegimeRoute = '/admin/regimes';
$adminRegimeIdPrefix = '/admin/regimes/';
$regimes = $regimes ?? [];
$currentRegime = $regime ?? null;
$objectives = $objectives ?? [];

$selectedObjectives = array_fill_keys($objectives, true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Suivi Régimes</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>">
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/dashboard.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<?= $this->include('admin/partials/nav') ?>

<main class="admin-page">
    <div class="admin-page-header">
        <div class="admin-page-header-copy">
            <span class="admin-page-kicker">Suivi des régimes</span>
            <h1 class="admin-page-title">Régimes disponibles</h1>
            <p class="admin-page-subtitle">Gérez la bibliothèque de régimes et ajustez chaque programme dans un panneau de paramétrage clair.</p>
        </div>
        <div class="admin-page-header-chip">
            <i class="bi bi-clipboard2-heart"></i>
            <span><?= count($regimes) ?> régimes actifs</span>
        </div>
    </div>

    <div class="row gx-4 gy-4 align-items-stretch admin-layout">
        <section class="col-12 col-lg-8 d-flex">
            <div class="admin-card admin-card-catalog d-flex flex-column w-100">
                <div class="admin-card-header">
                    <div>
                        <h2 class="admin-card-title">Catalogue des régimes</h2>
                        <p class="admin-card-subtitle">Sélectionnez un régime pour le modifier à droite.</p>
                    </div>
                    <span class="admin-chip"><?= count($regimes) ?> éléments</span>
                </div>

                <div class="table-responsive flex-grow-1">
                    <table class="table admin-table mb-0">
                        <thead>
                        <tr>
                            <th class="p-3">Nom</th>
                            <th class="p-3">Durée</th>
                            <th class="p-3">Calories</th>
                            <th class="p-3">Prix/Semaine</th>
                            <th class="p-3">Composition</th>
                            <th class="p-3 text-end">Actions</th>
                        </tr>
                        </thead>
                    <tbody class="bg-white">
                        <?php if (! empty($regimes)): ?>
                            <?php foreach ($regimes as $item): ?>
                                <?php
                                $isActive = isset($currentRegime['id']) && (int) $currentRegime['id'] === (int) $item['id'];
                                $meat = (float) ($item['meat_percent'] ?? 0);
                                $fish = (float) ($item['fish_percent'] ?? 0);
                                $poultry = (float) ($item['poultry_percent'] ?? 0);
                                ?>
                                <tr class="<?= $isActive ? 'is-active' : '' ?>">
                                    <td class="align-middle p-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="admin-avatar-pill">
                                                <?= htmlspecialchars(mb_strtoupper(mb_substr((string) ($item['name'] ?? 'R'), 0, 1)), ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-ink"><?= htmlspecialchars((string) ($item['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                                                <div class="admin-text-muted small"><?= htmlspecialchars((string) ($item['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle p-3 text-muted"><?= htmlspecialchars((string) ($item['duration_weeks'] ?? ''), ENT_QUOTES, 'UTF-8') ?> semaines</td>
                                    <td class="align-middle p-3 text-success fw-semibold"><?= htmlspecialchars((string) ($item['calorie_target'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="align-middle p-3"><?= htmlspecialchars(number_format((float) ($item['price_per_week'] ?? 0), 2, ',', ' '), ENT_QUOTES, 'UTF-8') ?> €</td>
                                    <td class="align-middle p-3">
                                        <div class="admin-composition">
                                            <div class="comp-bar">
                                                <span style="width: <?= $meat ?>%; background:#0d7c3a"></span>
                                            </div>
                                            <div class="comp-bar">
                                                <span style="width: <?= $fish ?>%; background:#4eb36a"></span>
                                            </div>
                                            <div class="comp-bar">
                                                <span style="width: <?= $poultry ?>%; background:#90c79a"></span>
                                            </div>
                                            <div class="admin-text-muted small"><?= $meat ?>% viande · <?= $fish ?>% poisson · <?= $poultry ?>% volaille</div>
                                        </div>
                                    </td>
                                    <td class="align-middle p-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="<?= htmlspecialchars($adminRegimeIdPrefix . $item['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-success btn-sm admin-action">Modifier</a>
                                            <form action="<?= htmlspecialchars($adminRegimeIdPrefix . $item['id'], ENT_QUOTES, 'UTF-8') ?>" method="post" onsubmit="return confirm('Supprimer ce régime ?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-outline-danger btn-sm admin-action">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center admin-text-muted">Aucun régime disponible pour le moment.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </section>

        <aside class="col-12 col-lg-4 d-flex">
            <div class="admin-card admin-card-editor d-flex flex-column w-100">
                <div class="admin-card-header admin-card-header-compact">
                    <div>
                        <h2 class="admin-card-title"><?= $currentRegime ? 'Modifier un régime' : 'Ajouter un régime' ?></h2>
                        <p class="admin-card-subtitle">Paramètres du régime sélectionné.</p>
                    </div>
                    <?php if ($currentRegime): ?>
                        <a href="<?= esc('/admin/regimes') ?>" class="btn btn-sm btn-outline-success">Nouveau</a>
                    <?php endif; ?>
                </div>

                <div class="admin-card-body">
                    <form method="post" action="<?= htmlspecialchars($currentRegime ? $adminRegimeIdPrefix . $currentRegime['id'] : $adminRegimeRoute, ENT_QUOTES, 'UTF-8') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="regime-name" class="form-label">Nom du régime</label>
                            <input id="regime-name" name="name" value="<?= htmlspecialchars((string) ($currentRegime['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="text" placeholder="Ex. Régime Léger Cardio">
                        </div>

                        <div class="mb-3">
                            <label for="regime-description" class="form-label">Description</label>
                            <textarea id="regime-description" name="description" rows="4" class="form-control soft-input" placeholder="Description du régime"><?= htmlspecialchars((string) ($currentRegime['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="regime-calories" class="form-label">Calories</label>
                                <input id="regime-calories" name="calorie_target" value="<?= htmlspecialchars((string) ($currentRegime['calorie_target'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="number" min="1">
                            </div>
                            <div class="col-6">
                                <label for="regime-price" class="form-label">Prix / semaine</label>
                                <input id="regime-price" name="price_per_week" value="<?= htmlspecialchars((string) ($currentRegime['price_per_week'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="number" step="0.01" min="0.01">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="regime-duration" class="form-label">Durée (semaines)</label>
                                <input id="regime-duration" name="duration_weeks" value="<?= htmlspecialchars((string) ($currentRegime['duration_weeks'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="number" min="1">
                            </div>
                            <div class="col-6">
                                <label for="regime-weight" class="form-label">Variation poids (%)</label>
                                <input id="regime-weight" name="weight_change_percent" value="<?= htmlspecialchars((string) ($currentRegime['weight_change_percent'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="number" step="0.01">
                            </div>
                        </div>

                        <div class="admin-section">
                            <div class="admin-section-title">Composition</div>
                            <div class="row g-2">
                                <div class="col-4">
                                    <label for="regime-meat" class="form-label small text-success">Viande</label>
                                    <input id="regime-meat" name="meat_percent" value="<?= htmlspecialchars((string) ($currentRegime['meat_percent'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="number" step="0.01" min="0" max="100">
                                </div>
                                <div class="col-4">
                                    <label for="regime-fish" class="form-label small text-success">Poisson</label>
                                    <input id="regime-fish" name="fish_percent" value="<?= htmlspecialchars((string) ($currentRegime['fish_percent'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="number" step="0.01" min="0" max="100">
                                </div>
                                <div class="col-4">
                                    <label for="regime-poultry" class="form-label small text-success">Volaille</label>
                                    <input id="regime-poultry" name="poultry_percent" value="<?= htmlspecialchars((string) ($currentRegime['poultry_percent'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="number" step="0.01" min="0" max="100">
                                </div>
                            </div>
                            <div class="admin-text-muted small mt-2">La somme des trois pourcentages doit faire 100%.</div>
                        </div>

                        <div class="mb-3">
                            <div class="admin-section-title">Objectifs liés</div>
                            <div class="admin-options">
                                <?php foreach (['loss' => 'Perte', 'gain' => 'Gain', 'ideal' => 'Idéal'] as $value => $label): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="objectives[]" value="<?= esc($value) ?>" id="obj-<?= esc($value) ?>" <?= isset($selectedObjectives[$value]) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="obj-<?= esc($value) ?>"><?= esc($label) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="regime-active" class="form-label">Statut</label>
                            <select id="regime-active" name="is_active" class="form-select soft-input">
                                <option value="1" <?= ((int) ($currentRegime['is_active'] ?? 1) === 1) ? 'selected' : '' ?>>Actif</option>
                                <option value="0" <?= ((int) ($currentRegime['is_active'] ?? 1) === 0) ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        </div>

                        <div class="admin-card-footer">
                            <button type="submit" class="btn btn-success w-100">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</main>
</body>
</html>
