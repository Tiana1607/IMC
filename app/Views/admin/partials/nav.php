<?php
$currentPath = current_url() ?? '';

function is_active_path(string $current, string $path): bool
{
    return $path !== '' && (strpos($current, $path) !== false);
}

$dashboardActive = is_active_path($currentPath, '/admin') && !is_active_path($currentPath, '/admin/regimes') && !is_active_path($currentPath, '/admin/activities');
$regimesActive = is_active_path($currentPath, '/admin/regimes');
$activitiesActive = is_active_path($currentPath, '/admin/activities');
?>

<header class="admin-topbar">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-heart-pulse-fill" style="font-size: 1.5rem; color: var(--primary-green);"></i>
                <span class="admin-brand">VitalPath</span>
            </div>

            <nav class="admin-nav d-none d-md-flex gap-2">
                <a href="<?= site_url('admin') ?>" class="<?= $dashboardActive ? 'is-active' : '' ?>">Dashboard</a>
                <a href="<?= site_url('/admin/regimes') ?>" class="<?= $regimesActive ? 'is-active' : '' ?>">Régimes</a>
                <a href="<?= site_url('/admin/activities') ?>" class="<?= $activitiesActive ? 'is-active' : '' ?>">Activités</a>
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
                <a href="<?= site_url('admin') ?>" class="btn btn-sm btn-outline-secondary">Dashboard</a>
                <a href="<?= site_url('/admin/regimes') ?>" class="btn btn-sm btn-outline-secondary">Régimes</a>
                <a href="<?= site_url('/admin/activities') ?>" class="btn btn-sm btn-outline-secondary">Activités</a>
            </div>
        </div>
    </div>
</header>
