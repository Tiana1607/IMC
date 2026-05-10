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
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lexend', sans-serif; background: #f5f7f8; color: #172019; }
        .admin-topbar { position: sticky; top: 0; z-index: 50; display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.25rem; background: rgba(245, 247, 248, 0.9); backdrop-filter: blur(14px); border-bottom: 1px solid #d8e0e2; }
        .admin-brand { display: flex; align-items: center; gap: .75rem; }
        .admin-brand-mark { display: inline-flex; width: 2.25rem; height: 2.25rem; border-radius: .9rem; align-items: center; justify-content: center; background: linear-gradient(135deg, #0d7c3a, #2f8f50); color: #fff; font-weight: 700; }
        .admin-brand-title { font-size: 1rem; font-weight: 700; color: #0b6240; line-height: 1.1; }
        .admin-brand-subtitle { font-size: .74rem; color: #637172; line-height: 1.1; }
        .admin-nav { display: none; gap: .5rem; }
        .admin-nav-link { padding: .6rem .95rem; border-radius: 9999px; color: #496164; font-size: .92rem; transition: all .18s ease; }
        .admin-nav-link:hover { background: #eaf1f1; color: #0b6240; }
        .admin-nav-link.is-active { background: #11784b; color: #fff; box-shadow: 0 10px 24px rgba(17, 120, 75, .18); }
        .admin-avatar { width: 2.45rem; height: 2.45rem; border-radius: 9999px; background: linear-gradient(135deg, #d6eaee, #9ac8d1); border: 1px solid #bbd3d8; }
        .panel { background: rgba(255,255,255,.78); border: 1px solid #d8e0e2; box-shadow: 0 16px 40px rgba(16, 45, 25, .06); }
        .soft-input { background: #f8fbfc; border: 1px solid #c7d6d9; }
        .soft-input:focus { border-color: #11784b; box-shadow: 0 0 0 3px rgba(17, 120, 75, .12); }
        .meter { height: .5rem; background: #dfe7e9; border-radius: 9999px; overflow: hidden; }
        .meter > span { display: block; height: 100%; }
        @media (min-width: 768px) { .admin-nav { display: flex; } }
    </style>
</head>
<body>
<?= $this->include('admin/partials/nav') ?>

<main class="mx-auto max-w-7xl px-4 py-6 md:px-8">
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#11784b]">Suivi des activités</p>
            <h1 class="mt-1 text-3xl font-bold text-[#132116]">Activités disponibles</h1>
            <p class="mt-2 max-w-2xl text-sm text-[#637172]">Structure équivalente à la page des régimes: liste à gauche, paramètres à droite, sans les blocs additionnels du mockup.</p>
        </div>
        <a href="<?= htmlspecialchars($adminActivityRoute, ENT_QUOTES, 'UTF-8') ?>" class="inline-flex items-center justify-center rounded-full bg-[#11784b] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[#0f6a41]">Ajouter une activité</a>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        <section class="panel overflow-hidden rounded-2xl lg:col-span-7">
            <div class="border-b border-[#d8e0e2] bg-[#f1f5f6] px-4 py-3">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-[#132116]">Catalogue des activités</h2>
                        <p class="text-sm text-[#637172]">Liste de gauche, modification à droite.</p>
                    </div>
                    <div class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-[#11784b]"><?= count($activities) ?> éléments</div>
                </div>
            </div>

            <div class="divide-y divide-[#e0e7e9] bg-white">
                <?php if (! empty($activities)): ?>
                    <?php foreach ($activities as $item): ?>
                        <?php
                        $isActive = isset($currentActivity['id']) && (int) $currentActivity['id'] === (int) $item['id'];
                        $intensity = strtolower((string) ($item['intensity'] ?? 'low'));
                        $intensityData = $intensityStyles[$intensity] ?? $intensityStyles['low'];
                        ?>
                        <div class="flex items-center gap-4 px-4 py-4 transition hover:bg-[#f4f9fa] <?= $isActive ? 'bg-[#eef7f8]' : '' ?>">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#e2f0f3] text-[#11784b] font-bold">
                                <?= esc(mb_strtoupper(mb_substr($item['name'] ?? 'A', 0, 1))) ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="truncate font-semibold text-[#132116]"><?= htmlspecialchars((string) ($item['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h3>
                                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold <?= htmlspecialchars((string) ($intensityData['class'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) ($intensityData['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                    <p class="mt-1 text-sm text-[#637172]"><?= htmlspecialchars((string) ($item['calories_per_hour'] ?? '0'), ENT_QUOTES, 'UTF-8') ?> cal/h · <?= htmlspecialchars((string) (($item['equipment_needed'] ?? '') !== '' ? $item['equipment_needed'] : 'Sans équipement'), ENT_QUOTES, 'UTF-8') ?></p>
                                <div class="mt-3 flex items-center gap-3 text-xs text-[#637172]">
                                    <span class="rounded-full bg-[#edf4ef] px-2 py-1 text-[#11784b]">Statut: <?= ((int) ($item['is_active'] ?? 1) === 1) ? 'Actif' : 'Inactif' ?></span>
                                    <?php if (! empty($item['difficulty_level'])): ?>
                                        <span class="rounded-full bg-[#f2f6f7] px-2 py-1"><?= htmlspecialchars((string) ($item['difficulty_level'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="<?= htmlspecialchars($adminActivityIdPrefix . $item['id'], ENT_QUOTES, 'UTF-8') ?>" class="rounded-lg px-3 py-2 text-sm font-semibold text-[#11784b] hover:bg-[#e7f4ec]">Modifier</a>
                                <form action="<?= htmlspecialchars($adminActivityIdPrefix . $item['id'], ENT_QUOTES, 'UTF-8') ?>" method="post" onsubmit="return confirm('Supprimer cette activité ?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="rounded-lg px-3 py-2 text-sm font-semibold text-[#c0392b] hover:bg-[#fdecec]">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="px-4 py-10 text-center text-sm text-[#637172]">Aucune activité disponible pour le moment.</div>
                <?php endif; ?>
            </div>
        </section>

        <aside class="panel rounded-2xl p-5 lg:col-span-5">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-[#132116]"><?= $currentActivity ? 'Modifier une activité' : 'Ajouter une activité' ?></h2>
                    <p class="mt-1 text-sm text-[#637172]">Paramètres de suivi et caractéristiques principales.</p>
                </div>
                <?php if ($currentActivity): ?>
                    <a href="<?= esc('/admin/activities') ?>" class="text-sm font-semibold text-[#11784b]">Nouvelle</a>
                <?php endif; ?>
            </div>

            <form class="space-y-4" method="post" action="<?= htmlspecialchars($currentActivity ? $adminActivityIdPrefix . $currentActivity['id'] : $adminActivityRoute, ENT_QUOTES, 'UTF-8') ?>">
                <?= csrf_field() ?>

                <div>
                    <label for="activity-name" class="mb-1 block text-sm font-semibold text-[#314042]">Nom de l’activité</label>
                    <input id="activity-name" name="name" value="<?= htmlspecialchars((string) ($currentActivity['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="text" placeholder="Ex. Marche rapide">
                </div>

                <div>
                    <label for="activity-description" class="mb-1 block text-sm font-semibold text-[#314042]">Description</label>
                    <textarea id="activity-description" name="description" rows="4" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" placeholder="Description de l’activité"><?= htmlspecialchars((string) ($currentActivity['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="activity-calories" class="mb-1 block text-sm font-semibold text-[#314042]">Calories / heure</label>
                        <input id="activity-calories" name="calories_per_hour" value="<?= htmlspecialchars((string) ($currentActivity['calories_per_hour'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="number" min="1">
                    </div>
                    <div>
                        <label for="activity-intensity" class="mb-1 block text-sm font-semibold text-[#314042]">Intensité</label>
                        <select id="activity-intensity" name="intensity" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none">
                            <?php foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label): ?>
                                <option value="<?= esc($value) ?>" <?= (($currentActivity['intensity'] ?? 'low') === $value) ? 'selected' : '' ?>><?= esc($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="activity-equipment" class="mb-1 block text-sm font-semibold text-[#314042]">Équipement nécessaire</label>
                    <input id="activity-equipment" name="equipment_needed" value="<?= htmlspecialchars((string) ($currentActivity['equipment_needed'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="text" placeholder="Ex. Tapis, haltères">
                </div>

                <div>
                    <label for="activity-difficulty" class="mb-1 block text-sm font-semibold text-[#314042]">Niveau de difficulté</label>
                    <select id="activity-difficulty" name="difficulty_level" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none">
                        <option value="">--</option>
                        <?php foreach (['beginner' => 'Débutant', 'intermediate' => 'Intermédiaire', 'advanced' => 'Avancé'] as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= (($currentActivity['difficulty_level'] ?? '') === $value) ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <div class="mb-1 block text-sm font-semibold text-[#314042]">Objectifs liés</div>
                    <div class="grid grid-cols-3 gap-2">
                        <?php foreach (['loss' => 'Perte', 'gain' => 'Gain', 'ideal' => 'Idéal'] as $value => $label): ?>
                            <label class="flex items-center gap-2 rounded-xl border border-[#cddbdd] bg-[#f8fbfc] px-3 py-2 text-sm text-[#314042]">
                                <input type="checkbox" name="objectives[]" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= isset($selectedObjectives[$value]) ? 'checked' : '' ?>>
                                <span><?= esc($label) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <label for="activity-active" class="mb-1 block text-sm font-semibold text-[#314042]">Statut</label>
                    <select id="activity-active" name="is_active" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none">
                        <option value="1" <?= ((int) ($currentActivity['is_active'] ?? 1) === 1) ? 'selected' : '' ?>>Actif</option>
                        <option value="0" <?= ((int) ($currentActivity['is_active'] ?? 1) === 0) ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>

                <button type="submit" class="w-full rounded-xl bg-[#11784b] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[#0f6a41]">Enregistrer</button>
            </form>
        </aside>
    </div>
</main>
</body>
</html>
