<?php
$adminActivityRoute = '/admin/activities';
$adminActivityIdPrefix = '/admin/activities/';
$activities = $activities ?? [];
$currentActivity = $activity ?? null;
$objectives = $objectives ?? [];

$selectedObjectives = array_fill_keys($objectives, true);

$intensityStyles = [
    'low' => ['label' => 'Bas', 'class' => 'bg-[#dff3e4] text-[#0d7c3a]'],
    'medium' => ['label' => 'Moyen', 'class' => 'bg-[#e7f0df] text-[#48662e]'],
    'high' => ['label' => 'Élevé', 'class' => 'bg-[#f1e7d8] text-[#8a5b1a]'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Suivi Activités</title>
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
            <span class="admin-page-kicker">Suivi des activités</span>
            <h1 class="admin-page-title">Activités disponibles</h1>
            <p class="admin-page-subtitle">Supervisez les activités proposées et ajustez leurs paramètres en quelques clics.</p>
        </div>
        <div class="admin-page-header-chip">
            <i class="bi bi-activity"></i>
            <span><?= count($activities) ?> activités actives</span>
        </div>
    </div>

    <div class="row gx-4 gy-4 align-items-stretch admin-layout">
        <section class="col-12 col-lg-8 d-flex">
            <div class="admin-card admin-card-catalog d-flex flex-column w-100">
                <div class="admin-card-header">
                    <div>
                        <h2 class="admin-card-title">Catalogue des activités</h2>
                        <p class="admin-card-subtitle">Liste de gauche, modification à droite.</p>
                    </div>
                    <span class="admin-chip"><?= count($activities) ?> éléments</span>
                </div>

                <div class="list-group list-group-flush admin-list flex-grow-1">
                <?php if (! empty($activities)): ?>
                    <?php foreach ($activities as $item): ?>
                        <?php
                        $isActive = isset($currentActivity['id']) && (int) $currentActivity['id'] === (int) $item['id'];
                        $intensity = strtolower((string) ($item['intensity'] ?? 'low'));
                        $intensityData = $intensityStyles[$intensity] ?? $intensityStyles['low'];
                        ?>
                        <div class="list-group-item admin-list-item d-flex align-items-start gap-3 <?= $isActive ? 'is-active' : '' ?>">
                            <div class="admin-avatar-pill">
                                <?= esc(mb_strtoupper(mb_substr($item['name'] ?? 'A', 0, 1))) ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-semibold"><?= htmlspecialchars((string) ($item['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h6>
                                        <div class="admin-text-muted small"><?= htmlspecialchars((string) ($item['calories_per_hour'] ?? '0'), ENT_QUOTES, 'UTF-8') ?> cal/h · <?= htmlspecialchars((string) (($item['equipment_needed'] ?? '') !== '' ? $item['equipment_needed'] : 'Sans équipement'), ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge admin-badge mb-1"><?= htmlspecialchars((string) ($intensityData['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                        <div class="admin-text-muted small"><?= ((int) ($item['is_active'] ?? 1) === 1) ? 'Actif' : 'Inactif' ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2 align-items-end">
                                <a href="<?= htmlspecialchars($adminActivityIdPrefix . $item['id'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-success btn-sm admin-action">Modifier</a>
                                <form action="<?= htmlspecialchars($adminActivityIdPrefix . $item['id'], ENT_QUOTES, 'UTF-8') ?>" method="post" onsubmit="return confirm('Supprimer cette activité ?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-outline-danger btn-sm admin-action">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center admin-text-muted">Aucune activité disponible pour le moment.</div>
                <?php endif; ?>
            </div>
            </div>
        </section>
        <aside class="col-12 col-lg-4 d-flex">
            <div class="admin-card admin-card-editor d-flex flex-column w-100">
                <div class="admin-card-header admin-card-header-compact">
                    <div>
                        <h2 class="admin-card-title"><?= $currentActivity ? 'Modifier une activité' : 'Ajouter une activité' ?></h2>
                        <p class="admin-card-subtitle">Paramètres de suivi et caractéristiques principales.</p>
                    </div>
                    <?php if ($currentActivity): ?>
                        <a href="<?= esc('/admin/activities') ?>" class="btn btn-sm btn-outline-success">Nouvelle</a>
                    <?php endif; ?>
                </div>

                <div class="admin-card-body">
                    <form method="post" action="<?= htmlspecialchars($currentActivity ? $adminActivityIdPrefix . $currentActivity['id'] : $adminActivityRoute, ENT_QUOTES, 'UTF-8') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="activity-name" class="form-label">Nom de l’activité</label>
                            <input id="activity-name" name="name" value="<?= htmlspecialchars((string) ($currentActivity['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="text" placeholder="Ex. Marche rapide">
                        </div>

                        <div class="mb-3">
                            <label for="activity-description" class="form-label">Description</label>
                            <textarea id="activity-description" name="description" rows="4" class="form-control soft-input" placeholder="Description de l’activité"><?= htmlspecialchars((string) ($currentActivity['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label for="activity-calories" class="form-label">Calories / heure</label>
                                <input id="activity-calories" name="calories_per_hour" value="<?= htmlspecialchars((string) ($currentActivity['calories_per_hour'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="number" min="1">
                            </div>
                            <div class="col-6">
                                <label for="activity-intensity" class="form-label">Intensité</label>
                                <select id="activity-intensity" name="intensity" class="form-select soft-input">
                                    <?php foreach (['low' => 'Bas', 'medium' => 'Moyen', 'high' => 'Élevé'] as $value => $label): ?>
                                        <option value="<?= esc($value) ?>" <?= (($currentActivity['intensity'] ?? 'low') === $value) ? 'selected' : '' ?>><?= esc($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="activity-equipment" class="form-label">Équipement nécessaire</label>
                            <input id="activity-equipment" name="equipment_needed" value="<?= htmlspecialchars((string) ($currentActivity['equipment_needed'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="form-control soft-input" type="text" placeholder="Ex. Tapis, haltères">
                        </div>

                        <div class="mb-3">
                            <label for="activity-difficulty" class="form-label">Niveau de difficulté</label>
                            <select id="activity-difficulty" name="difficulty_level" class="form-select soft-input">
                                <option value="">--</option>
                                <?php foreach (['beginner' => 'Débutant', 'intermediate' => 'Intermédiaire', 'advanced' => 'Avancé'] as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= (($currentActivity['difficulty_level'] ?? '') === $value) ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="admin-section-title">Objectifs liés</div>
                            <div class="admin-options">
                                <?php foreach (['loss' => 'Perte', 'gain' => 'Gain', 'ideal' => 'Idéal'] as $value => $label): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="objectives[]" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" id="act-obj-<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= isset($selectedObjectives[$value]) ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="act-obj-<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"><?= esc($label) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="activity-active" class="form-label">Statut</label>
                            <select id="activity-active" name="is_active" class="form-select soft-input">
                                <option value="1" <?= ((int) ($currentActivity['is_active'] ?? 1) === 1) ? 'selected' : '' ?>>Actif</option>
                                <option value="0" <?= ((int) ($currentActivity['is_active'] ?? 1) === 0) ? 'selected' : '' ?>>Inactif</option>
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
