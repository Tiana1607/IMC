<?php
$currentPath = strtolower(trim(service('uri')->getPath(), '/'));

$navActive = static function (string $needle) use ($currentPath): string {
    if ($needle === 'dashboard') {
        return ($currentPath === '' || strpos($currentPath, 'dashboard') === 0 || strpos($currentPath, 'admin') === 0) ? 'active' : '';
    }
    return strpos($currentPath, $needle) !== false ? 'active' : '';
};

$user = $user ?? [];
$imc = $imc ?? null;
$imcCategory = $imcCategory ?? null;
$objectiveLabel = $objectiveLabel ?? 'Non défini';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mon profil - VitalPath</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.0/font/bootstrap-icons.css" />
</head>
<body>
    <div class="wrapper" style="min-height: 100vh; display: flex; flex-direction: column;">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="<?= site_url('dashboard') ?>">
                    <i class="bi bi-heart-pulse"></i> VitalPath
                </a>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('dashboard') ?>">Tableau de bord</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="<?= site_url('profile/view') ?>">Mon profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('auth/logout') ?>">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow-1" style="background-color: #f8f9fa;">
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <!-- Flash Messages -->
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Profile Card -->
                        <div class="card shadow-lg mb-4">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h4 class="mb-0"><i class="bi bi-person-circle"></i> Mon profil</h4>
                                <a href="<?= site_url('profile/edit') ?>" class="btn btn-sm btn-light">
                                    <i class="bi bi-pencil"></i> Éditer
                                </a>
                            </div>
                            <div class="card-body p-4">
                                <!-- Nom et Email -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="text-secondary small">Nom complet</label>
                                        <p class="fs-5 fw-bold mb-0">
                                            <?= htmlspecialchars($user['name'] ?? 'Non défini') ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary small">Email</label>
                                        <p class="fs-5 fw-bold mb-0">
                                            <?= htmlspecialchars($user['email'] ?? 'Non défini') ?>
                                        </p>
                                    </div>
                                </div>

                                <hr />

                                <!-- Métriques Principales -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="bi bi-person-standing display-6 text-primary"></i>
                                            <p class="text-secondary small mt-2 mb-1">Poids</p>
                                            <p class="fs-5 fw-bold mb-0">
                                                <?= isset($user['weight_kg']) ? number_format((float) $user['weight_kg'], 1) . ' kg' : 'N/A' ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="bi bi-rulers display-6 text-info"></i>
                                            <p class="text-secondary small mt-2 mb-1">Taille</p>
                                            <p class="fs-5 fw-bold mb-0">
                                                <?= isset($user['height_cm']) ? number_format((float) $user['height_cm'], 0) . ' cm' : 'N/A' ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="bi bi-activity display-6 text-success"></i>
                                            <p class="text-secondary small mt-2 mb-1">IMC</p>
                                            <p class="fs-5 fw-bold mb-0">
                                                <?= $imc !== null ? number_format((float) $imc, 1) : 'N/A' ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- IMC Category -->
                                <?php if ($imcCategory): ?>
                                    <div class="alert alert-info mb-4">
                                        <strong>Catégorie IMC :</strong>
                                        <span class="badge bg-info ms-2 fs-6">
                                            <?= htmlspecialchars($imcCategory) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <hr />

                                <!-- Âge et Objectif -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="text-secondary small">Âge</label>
                                        <p class="fs-5 fw-bold mb-0">
                                            <?= isset($user['age']) ? $user['age'] . ' ans' : 'Non défini' ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary small">Objectif</label>
                                        <p class="fs-5 fw-bold mb-0">
                                            <?= htmlspecialchars($objectiveLabel) ?>
                                        </p>
                                    </div>
                                </div>

                                <hr />

                                <!-- Gold Status -->
                                <div class="mb-4">
                                    <label class="text-secondary small">Statut Gold</label>
                                    <p class="mb-0">
                                        <?php if ((int) ($user['is_gold'] ?? 0) === 1): ?>
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="bi bi-star-fill"></i> Gold Member
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary fs-6">
                                                <i class="bi bi-star"></i> Membre Standard
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <a href="<?= site_url('dashboard') ?>" class="btn btn-primary">
                                        <i class="bi bi-arrow-left"></i> Retour au tableau de bord
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
