<?php
$currentPath = strtolower(trim(service('uri')->getPath(), '/'));

$navActive = static function (string $needle) use ($currentPath): string {
    if ($needle === 'dashboard') {
        return ($currentPath === '' || strpos($currentPath, 'dashboard') === 0 || strpos($currentPath, 'admin') === 0) ? 'active' : '';
    }
    return strpos($currentPath, $needle) !== false ? 'active' : '';
};

$user = $user ?? [];
$objectiveOptions = $objectiveOptions ?? [];
$errors = session()->getFlashdata('errors') ?? [];
$currentObjective = $user['objective'] ?? 'imc_ideal';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Éditer mon profil - VitalPath</title>
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
                            <a class="nav-link active" href="<?= site_url('profile/edit') ?>">Mon profil</a>
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
                    <div class="col-lg-6">
                        <div class="card shadow-lg">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0"><i class="bi bi-person-circle"></i> Éditer mon profil</h4>
                            </div>
                            <div class="card-body p-4">
                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?= session()->getFlashdata('success') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?= session()->getFlashdata('error') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="<?= site_url('profile/update') ?>" method="POST">
                                    <?= csrf_field() ?>

                                    <!-- Hauteur -->
                                    <div class="mb-3">
                                        <label for="height" class="form-label">
                                            <i class="bi bi-rulers"></i> Taille (cm)
                                        </label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            min="100"
                                            max="250"
                                            class="form-control <?= isset($errors['height']) ? 'is-invalid' : '' ?>"
                                            id="height"
                                            name="height"
                                            value="<?= old('height', $user['height_cm'] ?? '') ?>"
                                            required />
                                        <?php if (isset($errors['height'])): ?>
                                            <div class="invalid-feedback">
                                                <?= htmlspecialchars($errors['height']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Poids -->
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">
                                            <i class="bi bi-person-standing"></i> Poids (kg)
                                        </label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            min="20"
                                            max="300"
                                            class="form-control <?= isset($errors['weight']) ? 'is-invalid' : '' ?>"
                                            id="weight"
                                            name="weight"
                                            value="<?= old('weight', $user['weight_kg'] ?? '') ?>"
                                            required />
                                        <?php if (isset($errors['weight'])): ?>
                                            <div class="invalid-feedback">
                                                <?= htmlspecialchars($errors['weight']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Âge -->
                                    <div class="mb-3">
                                        <label for="age" class="form-label">
                                            <i class="bi bi-calendar"></i> Âge
                                        </label>
                                        <input
                                            type="number"
                                            min="13"
                                            max="120"
                                            class="form-control <?= isset($errors['age']) ? 'is-invalid' : '' ?>"
                                            id="age"
                                            name="age"
                                            value="<?= old('age', $user['age'] ?? '') ?>"
                                            required />
                                        <?php if (isset($errors['age'])): ?>
                                            <div class="invalid-feedback">
                                                <?= htmlspecialchars($errors['age']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Objectif -->
                                    <div class="mb-4">
                                        <label for="objective" class="form-label">
                                            <i class="bi bi-target"></i> Objectif
                                        </label>
                                        <select
                                            class="form-select <?= isset($errors['objective']) ? 'is-invalid' : '' ?>"
                                            id="objective"
                                            name="objective"
                                            required>
                                            <option value="">-- Choisir un objectif --</option>
                                            <?php foreach ($objectiveOptions as $key => $label): ?>
                                                <option value="<?= htmlspecialchars($key) ?>" <?= $currentObjective === $key ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($label) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['objective'])): ?>
                                            <div class="invalid-feedback">
                                                <?= htmlspecialchars($errors['objective']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Info IMC (Informative) -->
                                    <?php if ($user['imc_value'] !== null): ?>
                                        <div class="alert alert-info mb-4">
                                            <strong>IMC actuel :</strong>
                                            <?= number_format((float) $user['imc_value'], 1) ?>
                                            <span class="badge bg-info ms-2">
                                                <?= htmlspecialchars($user['imc_category'] ?? 'N/A') ?>
                                            </span>
                                            <p class="mt-2 mb-0 small">
                                                <em>Votre IMC sera recalculé automatiquement après mise à jour.</em>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Buttons -->
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <i class="bi bi-check-circle"></i> Mettre à jour
                                        </button>
                                        <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary flex-grow-1">
                                            <i class="bi bi-arrow-left"></i> Annuler
                                        </a>
                                    </div>
                                </form>
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
