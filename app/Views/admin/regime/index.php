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
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lexend', sans-serif; background: #f4f7f3; color: #172019; }
        .admin-topbar { position: sticky; top: 0; z-index: 50; display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.25rem; background: rgba(244, 247, 243, 0.9); backdrop-filter: blur(14px); border-bottom: 1px solid #d8e3d5; }
        .admin-brand { display: flex; align-items: center; gap: .75rem; }
        .admin-brand-mark { display: inline-flex; width: 2.25rem; height: 2.25rem; border-radius: .9rem; align-items: center; justify-content: center; background: linear-gradient(135deg, #0a8f40, #12753d); color: #fff; font-weight: 700; }
        .admin-brand-title { font-size: 1rem; font-weight: 700; color: #0e5d2d; line-height: 1.1; }
        .admin-brand-subtitle { font-size: .74rem; color: #5a6b5a; line-height: 1.1; }
        .admin-nav { display: none; gap: .5rem; }
        .admin-nav-link { padding: .6rem .95rem; border-radius: 9999px; color: #43604a; font-size: .92rem; transition: all .18s ease; }
        .admin-nav-link:hover { background: #e5f1e8; color: #0f5f2e; }
        .admin-nav-link.is-active { background: #0d7c3a; color: #fff; box-shadow: 0 10px 24px rgba(13, 124, 58, .18); }
        .admin-avatar { width: 2.45rem; height: 2.45rem; border-radius: 9999px; background: linear-gradient(135deg, #d3ead7, #93d7a5); border: 1px solid #b7d5bc; }
        .panel { background: rgba(255,255,255,.78); border: 1px solid #d8e3d5; box-shadow: 0 16px 40px rgba(16, 45, 25, .06); }
        .soft-input { background: #f8fbf7; border: 1px solid #c9d8c9; }
        .soft-input:focus { border-color: #0d7c3a; box-shadow: 0 0 0 3px rgba(13, 124, 58, .12); }
        .comp-bar { height: .5rem; background: #dbe7db; border-radius: 9999px; overflow: hidden; }
        .comp-bar > span { display: block; height: 100%; }
        @media (min-width: 768px) { .admin-nav { display: flex; } }
    </style>
</head>
<body>
<?= $this->include('admin/partials/nav') ?>

<main class="mx-auto max-w-7xl px-4 py-6 md:px-8">
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0d7c3a]">Suivi des régimes</p>
            <h1 class="mt-1 text-3xl font-bold text-[#132116]">Régimes disponibles</h1>
            <p class="mt-2 max-w-2xl text-sm text-[#5a6b5a]">Liste des régimes à gauche et panneau de paramétrage à droite, dans un style proche du mockup mais plus épuré.</p>
        </div>
        <a href="<?= htmlspecialchars($adminRegimeRoute, ENT_QUOTES, 'UTF-8') ?>" class="inline-flex items-center justify-center rounded-full bg-[#0d7c3a] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-green-900/10 transition hover:bg-[#0b6c32]">Ajouter un régime</a>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        <section class="panel overflow-hidden rounded-2xl lg:col-span-8">
            <div class="border-b border-[#d8e3d5] bg-[#eef4ee] px-4 py-3">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-[#132116]">Catalogue des régimes</h2>
                        <p class="text-sm text-[#5a6b5a]">Sélectionnez un régime pour le modifier à droite.</p>
                    </div>
                    <div class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-[#0d7c3a]"><?= count($regimes) ?> éléments</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead class="bg-[#f7faf7] text-xs uppercase tracking-[0.12em] text-[#647764]">
                        <tr>
                            <th class="px-4 py-3">Nom</th>
                            <th class="px-4 py-3">Durée</th>
                            <th class="px-4 py-3">Calories</th>
                            <th class="px-4 py-3">Prix/Semaine</th>
                            <th class="px-4 py-3">Composition</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e9e0] bg-white">
                        <?php if (! empty($regimes)): ?>
                            <?php foreach ($regimes as $item): ?>
                                <?php
                                $isActive = isset($currentRegime['id']) && (int) $currentRegime['id'] === (int) $item['id'];
                                $meat = (float) ($item['meat_percent'] ?? 0);
                                $fish = (float) ($item['fish_percent'] ?? 0);
                                $poultry = (float) ($item['poultry_percent'] ?? 0);
                                ?>
                                <tr class="transition hover:bg-[#f4faf5] <?= $isActive ? 'bg-[#eaf7ee]' : '' ?>">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#dff3e4] text-[#0d7c3a] font-bold">
                                                <?= htmlspecialchars(mb_strtoupper(mb_substr((string) ($item['name'] ?? 'R'), 0, 1)), ENT_QUOTES, 'UTF-8') ?>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-[#132116]"><?= htmlspecialchars((string) ($item['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                                                <div class="text-xs text-[#647764]"><?= htmlspecialchars((string) ($item['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-[#314032]"><?= htmlspecialchars((string) ($item['duration_weeks'] ?? ''), ENT_QUOTES, 'UTF-8') ?> semaines</td>
                                    <td class="px-4 py-4 text-sm text-[#0d7c3a] font-semibold"><?= htmlspecialchars((string) ($item['calorie_target'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="px-4 py-4 text-sm text-[#314032]"><?= htmlspecialchars(number_format((float) ($item['price_per_week'] ?? 0), 2, ',', ' '), ENT_QUOTES, 'UTF-8') ?> €</td>
                                    <td class="px-4 py-4">
                                        <div class="w-40 space-y-1">
                                            <div class="comp-bar">
                                                <span style="width: <?= $meat ?>%; background:#0d7c3a"></span>
                                            </div>
                                            <div class="comp-bar">
                                                <span style="width: <?= $fish ?>%; background:#4eb36a"></span>
                                            </div>
                                            <div class="comp-bar">
                                                <span style="width: <?= $poultry ?>%; background:#90c79a"></span>
                                            </div>
                                            <div class="text-xs text-[#647764]"><?= $meat ?>% viande · <?= $fish ?>% poisson · <?= $poultry ?>% volaille</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="<?= htmlspecialchars($adminRegimeIdPrefix . $item['id'], ENT_QUOTES, 'UTF-8') ?>" class="rounded-lg px-3 py-2 text-sm font-semibold text-[#0d7c3a] hover:bg-[#e8f4ec]">Modifier</a>
                                            <form action="<?= htmlspecialchars($adminRegimeIdPrefix . $item['id'], ENT_QUOTES, 'UTF-8') ?>" method="post" onsubmit="return confirm('Supprimer ce régime ?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="rounded-lg px-3 py-2 text-sm font-semibold text-[#b21f1f] hover:bg-[#fdecec]">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-[#647764]">Aucun régime disponible pour le moment.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="panel rounded-2xl p-5 lg:col-span-4">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-[#132116]"><?= $currentRegime ? 'Modifier un régime' : 'Ajouter un régime' ?></h2>
                    <p class="mt-1 text-sm text-[#5a6b5a]">Panneau de paramétrage du régime sélectionné.</p>
                </div>
                <?php if ($currentRegime): ?>
                    <a href="<?= esc('/admin/regimes') ?>" class="text-sm font-semibold text-[#0d7c3a]">Nouveau</a>
                <?php endif; ?>
            </div>

            <form class="space-y-4" method="post" action="<?= htmlspecialchars($currentRegime ? $adminRegimeIdPrefix . $currentRegime['id'] : $adminRegimeRoute, ENT_QUOTES, 'UTF-8') ?>">
                <?= csrf_field() ?>

                <div>
                    <label for="regime-name" class="mb-1 block text-sm font-semibold text-[#314032]">Nom du régime</label>
                    <input id="regime-name" name="name" value="<?= htmlspecialchars((string) ($currentRegime['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="text" placeholder="Ex. Régime Léger Cardio">
                </div>

                <div>
                    <label for="regime-description" class="mb-1 block text-sm font-semibold text-[#314032]">Description</label>
                    <textarea id="regime-description" name="description" rows="4" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" placeholder="Description du régime"><?= htmlspecialchars((string) ($currentRegime['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="regime-calories" class="mb-1 block text-sm font-semibold text-[#314032]">Calories</label>
                        <input id="regime-calories" name="calorie_target" value="<?= htmlspecialchars((string) ($currentRegime['calorie_target'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="number" min="1">
                    </div>
                    <div>
                        <label for="regime-price" class="mb-1 block text-sm font-semibold text-[#314032]">Prix / semaine</label>
                        <input id="regime-price" name="price_per_week" value="<?= htmlspecialchars((string) ($currentRegime['price_per_week'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="number" step="0.01" min="0.01">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="regime-duration" class="mb-1 block text-sm font-semibold text-[#314032]">Durée (semaines)</label>
                        <input id="regime-duration" name="duration_weeks" value="<?= htmlspecialchars((string) ($currentRegime['duration_weeks'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="number" min="1">
                    </div>
                    <div>
                        <label for="regime-weight" class="mb-1 block text-sm font-semibold text-[#314032]">Variation poids (%)</label>
                        <input id="regime-weight" name="weight_change_percent" value="<?= htmlspecialchars((string) ($currentRegime['weight_change_percent'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="number" step="0.01">
                    </div>
                </div>

                <div class="space-y-3 rounded-2xl bg-[#f7faf7] p-4">
                    <div class="text-sm font-semibold text-[#314032]">Composition</div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="regime-meat" class="mb-1 block text-xs font-semibold text-[#0d7c3a]">Viande</label>
                            <input id="regime-meat" name="meat_percent" value="<?= htmlspecialchars((string) ($currentRegime['meat_percent'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="number" step="0.01" min="0" max="100">
                        </div>
                        <div>
                            <label for="regime-fish" class="mb-1 block text-xs font-semibold text-[#2f8f50]">Poisson</label>
                            <input id="regime-fish" name="fish_percent" value="<?= htmlspecialchars((string) ($currentRegime['fish_percent'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="number" step="0.01" min="0" max="100">
                        </div>
                        <div>
                            <label for="regime-poultry" class="mb-1 block text-xs font-semibold text-[#67996f]">Volaille</label>
                            <input id="regime-poultry" name="poultry_percent" value="<?= htmlspecialchars((string) ($currentRegime['poultry_percent'] ?? 0), ENT_QUOTES, 'UTF-8') ?>" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none" type="number" step="0.01" min="0" max="100">
                        </div>
                    </div>
                    <p class="text-xs text-[#647764]">La somme des trois pourcentages doit faire 100%.</p>
                </div>

                <div>
                    <div class="mb-1 block text-sm font-semibold text-[#314032]">Objectifs liés</div>
                    <div class="grid grid-cols-3 gap-2">
                        <?php foreach (['loss' => 'Perte', 'gain' => 'Gain', 'ideal' => 'Idéal'] as $value => $label): ?>
                            <label class="flex items-center gap-2 rounded-xl border border-[#cfdccf] bg-[#f8fbf7] px-3 py-2 text-sm text-[#314032]">
                                <input type="checkbox" name="objectives[]" value="<?= esc($value) ?>" <?= isset($selectedObjectives[$value]) ? 'checked' : '' ?>>
                                <span><?= esc($label) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <label for="regime-active" class="mb-1 block text-sm font-semibold text-[#314032]">Statut</label>
                    <select id="regime-active" name="is_active" class="soft-input w-full rounded-xl px-3 py-3 text-sm outline-none">
                        <option value="1" <?= ((int) ($currentRegime['is_active'] ?? 1) === 1) ? 'selected' : '' ?>>Actif</option>
                        <option value="0" <?= ((int) ($currentRegime['is_active'] ?? 1) === 0) ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>

                <button type="submit" class="w-full rounded-xl bg-[#0d7c3a] px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-green-900/10 transition hover:bg-[#0b6c32]">Enregistrer</button>
            </form>
        </aside>
    </div>
</main>
</body>
</html>
