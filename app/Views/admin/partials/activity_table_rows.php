<?php
if (!function_exists('admin_dashboard_initials')) {
    function admin_dashboard_initials(?string $name): string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return 'NA';
        }

        $parts = preg_split('/\s+/', $name);
        $first = strtoupper(substr((string) ($parts[0] ?? ''), 0, 1));
        $last = strtoupper(substr((string) ($parts[count($parts) - 1] ?? ''), 0, 1));

        return $first . ($last ?: '');
    }
}

if (!function_exists('admin_dashboard_gender_label')) {
    function admin_dashboard_gender_label(?string $gender): string
    {
        return match (strtoupper(trim((string) $gender))) {
            'M' => 'Homme',
            'F' => 'Femme',
            'O' => 'Autre',
            default => 'Non renseigné',
        };
    }
}

if (!function_exists('admin_dashboard_time_ago')) {
    function admin_dashboard_time_ago(?string $dateTime): string
    {
        if (empty($dateTime)) {
            return 'Jamais';
        }

        $timestamp = strtotime($dateTime);
        if ($timestamp === false) {
            return 'Inconnu';
        }

        $diff = time() - $timestamp;
        if ($diff < 60) {
            return 'Il y a moins d\'1 min';
        }
        if ($diff < 3600) {
            return 'Il y a ' . floor($diff / 60) . ' mins';
        }
        if ($diff < 86400) {
            return 'Il y a ' . floor($diff / 3600) . ' h';
        }

        return 'Il y a ' . floor($diff / 86400) . ' jours';
    }
}
?>

<?php if (empty($recentActivities)): ?>
    <tr>
        <td colspan="5" class="text-center text-muted py-4">Aucune activité récente pour le moment.</td>
    </tr>
<?php else: ?>
    <?php foreach ($recentActivities as $activity): ?>
        <?php
        $name = (string) ($activity['name'] ?? 'Utilisateur inconnu');
        $email = (string) ($activity['email'] ?? 'email@inconnu');
        $objectiveLabel = (string) ($activity['objective_label'] ?? 'Objectif non défini');
        $planLabel = (string) ($activity['plan_label'] ?? $objectiveLabel);
        $planHint = (string) ($activity['plan_hint'] ?? 'Aucun plan acheté');
        $genderLabel = admin_dashboard_gender_label($activity['gender'] ?? null);
        $isActive = (int) ($activity['is_active'] ?? 0) === 1;
        $isGold = (int) ($activity['is_gold'] ?? 0) === 1;
        $lastLogin = admin_dashboard_time_ago($activity['last_login'] ?? null);
        $initials = admin_dashboard_initials($name);
        $height = (string) ($activity['height_cm'] ?? '');
        $weight = (string) ($activity['weight_kg'] ?? '');
        $age = (string) ($activity['age'] ?? '');
        $imcValue = array_key_exists('imc_value', $activity) && $activity['imc_value'] !== null ? number_format((float) $activity['imc_value'], 2, ',', ' ') : 'N/D';
        $imcCategory = (string) ($activity['imc_category'] ?? 'N/D');
        $walletBalance = number_format((float) ($activity['wallet_balance'] ?? 0), 2, ',', ' ');
        $createdAt = (string) ($activity['created_at'] ?? '');
        $updatedAt = (string) ($activity['updated_at'] ?? '');
        $lastLoginRaw = (string) ($activity['last_login'] ?? '');
        $userId = (string) ($activity['id'] ?? '');
        ?>
        <tr>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle"
                        style="width: 2rem; height: 2rem; background: <?= $isActive ? 'rgba(0, 110, 47, 0.1)' : 'rgba(107, 114, 128, 0.1)' ?>; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 600;">
                        <?= esc($initials) ?>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: #1f2937;">
                            <?= esc($name) ?>
                        </div>
                        <div style="font-size: 0.85rem; color: #9ca3af;">
                            <?= esc($email) ?>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div style="font-weight: 600; color: #1f2937;">
                    <?= esc($planLabel) ?>
                </div>
                <div style="font-size: 0.82rem; color: #9ca3af;">
                    <?= esc($planHint) ?>
                </div>
            </td>
            <td>
                <span class="badge <?= $isActive ? 'badge-active' : 'badge-inactive' ?>">
                    <?= $isActive ? 'ACTIF' : 'INACTIF' ?>
                </span>
            </td>
            <td><?= esc($lastLogin) ?></td>
            <td>
                <button type="button"
                    class="btn btn-link p-0 js-open-user-modal"
                    style="color: var(--primary-green); text-decoration: none; font-weight: 600;"
                    data-user-id="<?= esc($userId) ?>"
                    data-user-name="<?= esc($name) ?>"
                    data-user-email="<?= esc($email) ?>"
                    data-user-gender="<?= esc((string) ($activity['gender'] ?? '')) ?>"
                    data-user-gender-label="<?= esc($genderLabel) ?>"
                    data-user-age="<?= esc($age) ?>"
                    data-user-height="<?= esc($height) ?>"
                    data-user-weight="<?= esc($weight) ?>"
                    data-user-objective="<?= esc((string) ($activity['objective'] ?? '')) ?>"
                    data-user-objective-label="<?= esc($objectiveLabel) ?>"
                    data-user-plan-label="<?= esc($planLabel) ?>"
                    data-user-plan-hint="<?= esc($planHint) ?>"
                    data-user-imc-value="<?= esc($imcValue) ?>"
                    data-user-imc-category="<?= esc($imcCategory) ?>"
                    data-user-wallet-balance="<?= esc($walletBalance) ?>"
                    data-user-is-gold="<?= $isGold ? '1' : '0' ?>"
                    data-user-is-active="<?= $isActive ? '1' : '0' ?>"
                    data-user-created-at="<?= esc($createdAt) ?>"
                    data-user-updated-at="<?= esc($updatedAt) ?>"
                    data-user-last-login="<?= esc($lastLoginRaw) ?>">
                    Gérer
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>