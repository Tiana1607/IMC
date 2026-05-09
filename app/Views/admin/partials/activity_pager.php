<?php
$activityFilter = $activityFilter ?? 'all';
$showAllActivities = (bool) ($showAllActivities ?? false);
$activityPage = max(1, (int) ($activityPage ?? 1));
$activityTotalPages = max(0, (int) ($activityTotalPages ?? 0));
$activityTotal = max(0, (int) ($activityTotal ?? 0));
$activityPerPage = max(1, (int) ($activityPerPage ?? 10));

$baseParams = [
    'activity_filter' => $activityFilter,
    'show_all' => $showAllActivities ? 1 : 0,
];
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
    <div class="text-muted" style="font-size: 0.9rem;">
        <?= esc($activityTotal) ?> résultat<?= $activityTotal > 1 ? 's' : '' ?>
        <?= $showAllActivities ? '(mode complet)' : 'affichés par pages de ' . $activityPerPage ?>
    </div>
    <?php if (! $showAllActivities && $activityTotalPages > 1): ?>
        <div class="btn-group" role="group" aria-label="Pagination activité admin">
            <button class="btn btn-outline-secondary js-activity-page"
                data-page="<?= esc(max(1, $activityPage - 1)) ?>"
                <?= $activityPage <= 1 ? 'disabled' : '' ?>>Précédent</button>
            <button class="btn btn-outline-secondary js-activity-page" disabled>
                Page <?= esc($activityPage) ?> / <?= esc($activityTotalPages) ?>
            </button>
            <button class="btn btn-outline-secondary js-activity-page"
                data-page="<?= esc(min($activityTotalPages, $activityPage + 1)) ?>"
                <?= $activityPage >= $activityTotalPages ? 'disabled' : '' ?>>Suivant</button>
        </div>
    <?php endif; ?>
</div>