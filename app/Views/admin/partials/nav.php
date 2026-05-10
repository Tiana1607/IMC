<?php
const ADMIN_DASHBOARD_ROUTE = '/admin';
const ADMIN_REGIMES_ROUTE = '/admin/regimes';
const ADMIN_ACTIVITIES_ROUTE = '/admin/activities';

$currentPath = current_url() ?? '';

$navItems = [
    ['label' => 'Dashboard', 'href' => ADMIN_DASHBOARD_ROUTE, 'active' => str_contains($currentPath, ADMIN_DASHBOARD_ROUTE) && ! str_contains($currentPath, ADMIN_REGIMES_ROUTE) && ! str_contains($currentPath, ADMIN_ACTIVITIES_ROUTE)],
    ['label' => 'Régimes', 'href' => ADMIN_REGIMES_ROUTE, 'active' => str_contains($currentPath, ADMIN_REGIMES_ROUTE)],
    ['label' => 'Activités', 'href' => ADMIN_ACTIVITIES_ROUTE, 'active' => str_contains($currentPath, ADMIN_ACTIVITIES_ROUTE)],
];
?>
<header class="admin-topbar">
    <div class="admin-brand">
        <span class="admin-brand-mark">V</span>
        <div>
            <div class="admin-brand-title">VitalPath</div>
            <div class="admin-brand-subtitle">Admin workspace</div>
        </div>
    </div>

    <nav class="admin-nav">
        <?php foreach ($navItems as $item): ?>
            <a class="admin-nav-link<?= $item['active'] ? ' is-active' : '' ?>" href="<?= htmlspecialchars((string) $item['href'], ENT_QUOTES, 'UTF-8') ?>" aria-label="<?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?>">
                <span><?= htmlspecialchars((string) $item['label'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="admin-avatar" aria-label="Admin profile"></div>
</header>
