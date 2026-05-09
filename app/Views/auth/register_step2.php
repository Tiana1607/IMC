<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitalPath | Inscription - Étape 2</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/register_step1.css') ?>">
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">

    <style>
        .goal-active {
            border-color: #006e2f !important;
            border-width: 2px !important;
            background-color: rgba(34, 197, 94, 0.05) !important;
        }
        .form-check.goal-choice {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .form-check.goal-choice .form-check-label {
            flex: 1;
            margin-bottom: 0;
        }
        .form-check.goal-choice .form-check-input {
            margin: 0;
            width: 1.05rem;
            height: 1.05rem;
        }
    </style>
</head>

<body>

    <header class="auth-topbar sticky-top d-flex align-items-center">
        <div class="container-fluid px-4 px-lg-5">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <a href="<?= site_url('/') ?>" class="text-decoration-none d-flex align-items-center gap-2">
                    <i class="bi bi-heart-pulse-fill text-success fs-3"></i>
                    <span class="brand">VitalPath</span>
                </a>

                <div class="d-flex align-items-center gap-3">
                    <span class="d-none d-md-inline small text-uppercase fw-semibold text-success">Calcul des
                        besoins</span>
                    <a href="<?= site_url('auth/login') ?>" class="btn btn-outline-success btn-sm rounded-pill px-3 text-decoration-none">Connexion</a>
                </div>
            </div>
        </div>
    </header>

    <main class="auth-shell">
        <div class="container-fluid h-100">
            <div class="row g-0 min-vh-100">

                <section
                    class="col-lg-6 d-none d-lg-flex auth-visual align-items-center justify-content-center p-4 p-xl-5 border-end">
                    <div class="auth-visual-card auth-copy p-4 p-xl-5">
                        <h1 class="fw-bold text-dark mb-3">Personnalisez votre parcours.</h1>
                        <p class="lead text-secondary mb-4">
                            Vos métriques nous aident à calculer vos besoins caloriques et élaborer un plan de nutrition
                            adapté à votre métabolisme.
                        </p>

                        <!-- BMI Card Preview -->
                        <div class="border rounded-3 p-4 bg-white mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="small text-uppercase fw-bold text-secondary"
                                        style="letter-spacing: 0.05em; font-size: 0.75rem;">Estimation actuelle</div>
                                    <h5 class="fw-bold text-dark mt-2">Analyse IMC</h5>
                                </div>
                                <span class="badge px-3 py-2" id="preview-badge"
                                    style="background-color: rgba(34, 197, 94, 0.25); color: #006e2f; border: 1px solid rgba(0, 110, 47, 0.25);">Plage
                                    saine</span>
                            </div>
                            <div class="d-flex align-items-end gap-2 mb-3">
                                <span class="fs-1 fw-bold text-success" id="preview-bmi">--</span>
                                <span class="text-secondary mb-2">kg/m²</span>
                            </div>
                            <!-- Progress/Scale -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-between small text-secondary mb-2">
                                    <span>Insuffisant</span>
                                    <span>Normal</span>
                                    <span>Surpoids</span>
                                </div>
                                <div class="progress position-relative" style="height: 12px;">
                                    <div class="progress-bar" id="preview-bar"
                                        style="width: 0%; background: linear-gradient(90deg, #3b82f6 0%, #22c55e 50%, #f97316 100%); transition: width 0.3s ease;">
                                    </div>
                                    <!-- Cursor indicator -->
                                    <div class="position-absolute" id="preview-cursor"
                                        style="left: 0%; top: 0; bottom: 0; margin: auto 0; width: 16px; height: 16px; transform: translateX(-50%); background: white; border: 2px solid #006e2f; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.12); transition: left 0.3s ease; z-index:5;">
                                    </div>
                                </div>
                                <div class="mt-2 small text-secondary" id="preview-category">--</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="col-lg-6 d-flex align-items-center justify-content-center p-3 p-md-4 p-xl-5 bg-white">
                    <div class="auth-card auth-copy py-2 py-lg-3">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span class="section-label text-success">Étape 2 sur 2</span>
                                <span class="small text-secondary">Métriques physiques</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 100%;"></div>
                            </div>
                        </div>

                        <h2 class="fw-bold mb-2 text-dark">Calculons vos besoins</h2>
                        <p class="text-secondary mb-4">Entre tes métriques pour finaliser ton profil.</p>

                        <?php $errors = session()->getFlashdata('errors') ?? []; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger border-0 rounded-3 mb-3">
                                <?= esc(session()->getFlashdata('error')) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Mobile BMI Card (visible on small screens) - compact with SVG ring -->
                        <div class="d-lg-none mb-4">
                            <div class="border rounded-3 p-3 bg-white" style="box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="small text-uppercase fw-bold text-secondary" style="letter-spacing: 0.04em; font-size: 0.7rem;">Estimation</div>
                                        <div class="fw-bold">IMC</div>
                                    </div>
                                    <span class="badge px-2 py-1" id="preview-badge-mobile" style="background-color: rgba(34, 197, 94, 0.25); color: #006e2f; border: 1px solid rgba(0, 110, 47, 0.25);">--</span>
                                </div>
                                <div class="d-flex align-items-center gap-3 mt-3">
                                    <div class="flex-grow-1">
                                        <div class="fs-3 fw-bold text-success" id="preview-bmi-mobile">--</div>
                                        <div class="small text-secondary" id="preview-category-mobile">--</div>
                                    </div>
                                    <div style="width:64px; height:64px;">
                                        <svg width="64" height="64" viewBox="0 0 64 64">
                                            <defs>
                                                <linearGradient id="mobileRingGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                                    <stop offset="0%" stop-color="#3b82f6" />
                                                    <stop offset="50%" stop-color="#22c55e" />
                                                    <stop offset="100%" stop-color="#f97316" />
                                                </linearGradient>
                                            </defs>
                                            <circle cx="32" cy="32" r="28" stroke="#e6eef0" stroke-width="6" fill="none" />
                                            <circle id="preview-ring-circle-mobile" cx="32" cy="32" r="28" stroke="url(#mobileRingGrad)" stroke-width="6" fill="none" stroke-linecap="round" transform="rotate(-90 32 32)" stroke-dasharray="175.84" stroke-dashoffset="175.84"></circle>
                                            <text id="preview-ring-text-mobile" x="32" y="37" text-anchor="middle" font-size="12" fill="#006e2f" font-weight="700"> </text>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="<?= site_url('auth/register/step2') ?>" method="post" class="needs-validation" novalidate>
                            <?= csrf_field() ?>

                            <!-- Taille et Poids -->
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label for="height" class="form-label fw-semibold">Taille (cm)</label>
                                    <input type="number" class="form-control metric-input" id="height" name="height"
                                        placeholder="175" required min="100" max="250" value="<?= old('height') ?>">
                                    <?php if (isset($errors['height'])): ?>
                                        <div class="text-danger small mt-1"><?= esc($errors['height']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-6">
                                    <label for="weight" class="form-label fw-semibold">Poids (kg)</label>
                                    <input type="number" class="form-control metric-input" id="weight" name="weight"
                                        placeholder="70" required min="30" max="300" step="0.1" value="<?= old('weight') ?>">
                                    <?php if (isset($errors['weight'])): ?>
                                        <div class="text-danger small mt-1"><?= esc($errors['weight']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Age -->
                            <div class="mb-3">
                                <label for="age" class="form-label fw-semibold">Âge</label>
                                <input type="number" class="form-control metric-input" id="age" name="age"
                                    placeholder="28" required min="13" max="120" value="<?= old('age') ?>">
                                <?php if (isset($errors['age'])): ?>
                                    <div class="text-danger small mt-1"><?= esc($errors['age']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Goal Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-3">Quel est ton objectif principal ?</label>
                                <div class="space-y-2">
                                    <!-- Option 1 -->
                                    <div class="form-check goal-choice">
                                        <input class="form-check-input goal-input" type="radio" name="goal"
                                            id="goal_gain" value="augmenter_poids" <?= old('goal', 'imc_ideal') === 'augmenter_poids' ? 'checked' : '' ?>>
                                        <label class="form-check-label w-100 p-3 border rounded-2 cursor-pointer"
                                            for="goal_gain" data-goal="augmenter_poids">
                                            <div class="d-flex gap-3">
                                                <div>
                                                    <i class="bi bi-graph-up-arrow text-success fs-4"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">Augmenter son poids</div>
                                                    <small class="text-secondary">Prendre progressivement de la masse
                                                        de manière saine</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Option 2 -->
                                    <div class="form-check goal-choice">
                                        <input class="form-check-input goal-input" type="radio" name="goal"
                                            id="goal_reduce" value="reduire_poids" <?= old('goal', 'imc_ideal') === 'reduire_poids' ? 'checked' : '' ?>>
                                        <label class="form-check-label w-100 p-3 border rounded-2 cursor-pointer"
                                            for="goal_reduce" data-goal="reduire_poids">
                                            <div class="d-flex gap-3">
                                                <div>
                                                    <i class="bi bi-graph-down-arrow text-success fs-4"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">Réduire son poids</div>
                                                    <small class="text-secondary">Perdre du gras avec un déficit
                                                        durable</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Option 3 -->
                                    <div class="form-check goal-choice">
                                        <input class="form-check-input goal-input" type="radio" name="goal"
                                            id="goal_ideal" value="imc_ideal" <?= old('goal', 'imc_ideal') === 'imc_ideal' ? 'checked' : '' ?>>
                                        <label class="form-check-label w-100 p-3 border rounded-2 cursor-pointer"
                                            for="goal_ideal" data-goal="imc_ideal">
                                            <div class="d-flex gap-3">
                                                <div>
                                                    <i class="bi bi-bullseye text-success fs-4"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">Atteindre son IMC idéal</div>
                                                    <small class="text-secondary">Viser une zone saine et équilibrée
                                                        selon ta morphologie</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <?php if (isset($errors['goal'])): ?>
                                    <div class="text-danger small mt-2"><?= esc($errors['goal']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- CTA Buttons -->
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-success rounded-pill py-3 fw-semibold">
                                    Terminer l'inscription
                                    <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                                <a href="<?= site_url('auth/register/step1') ?>"
                                    class="btn btn-outline-secondary rounded-pill py-3 fw-semibold">
                                    Retour à l'étape précédente
                                </a>
                            </div>

                            <p class="text-center small text-secondary">
                                En cliquant terminer, tu acceptes nos <a href="#"
                                    class="text-success text-decoration-none">conditions d'utilisation</a>
                                et notre <a href="#" class="text-success text-decoration-none">politique de
                                    confidentialité</a>.
                            </p>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const heightInput = document.getElementById("height");
            const weightInput = document.getElementById("weight");
            const ageInput = document.getElementById("age");
            const previewBMI = document.getElementById("preview-bmi");
            const previewCategory = document.getElementById("preview-category");
            const previewBar = document.getElementById("preview-bar");
            const previewCursor = document.getElementById("preview-cursor");
            const previewBadge = document.getElementById("preview-badge");

            // Fonction pour calculer et afficher l'IMC dynamiquement
            function calculateBMI() {
                const height = parseFloat(heightInput.value);
                const weight = parseFloat(weightInput.value);

                if (height && weight && height > 0 && weight > 0) {
                    const heightInMeters = height / 100;
                    const bmi = weight / (heightInMeters * heightInMeters);
                    previewBMI.textContent = bmi.toFixed(1);

                    // Catégorie d'IMC et couleur du badge
                    let category = "";
                    let badgeColor = "#006e2f";
                    let badgeBgColor = "rgba(34, 197, 94, 0.25)";
                    let badgeBorderColor = "rgba(0, 110, 47, 0.25)";
                    let badgeText = "Plage saine";
                    let cursorPosition = 0;

                    if (bmi < 18.5) {
                        category = "Sous-poids";
                        badgeColor = "#3b82f6";
                        badgeBgColor = "rgba(59, 130, 246, 0.25)";
                        badgeBorderColor = "rgba(59, 130, 246, 0.25)";
                        badgeText = "Sous-poids";
                        cursorPosition = (bmi / 18.5) * 25; // 0-25% de l'échelle
                    } else if (bmi < 25) {
                        category = "Normal";
                        badgeColor = "#006e2f";
                        badgeBgColor = "rgba(34, 197, 94, 0.25)";
                        badgeBorderColor = "rgba(0, 110, 47, 0.25)";
                        badgeText = "Plage saine";
                        cursorPosition = 25 + ((bmi - 18.5) / 6.5) * 25; // 25-50% de l'échelle
                    } else if (bmi < 30) {
                        category = "Surpoids";
                        badgeColor = "#f59e0b";
                        badgeBgColor = "rgba(245, 158, 11, 0.25)";
                        badgeBorderColor = "rgba(245, 158, 11, 0.25)";
                        badgeText = "Surpoids";
                        cursorPosition = 50 + ((bmi - 25) / 5) * 25; // 50-75% de l'échelle
                    } else {
                        category = "Obésité";
                        badgeColor = "#dc2626";
                        badgeBgColor = "rgba(220, 38, 38, 0.25)";
                        badgeBorderColor = "rgba(220, 38, 38, 0.25)";
                        badgeText = "Obésité";
                        cursorPosition = 75 + Math.min((bmi - 30) / 10 * 25, 25); // 75-100% de l'échelle
                    }

                    // Mettre à jour le texte de la catégorie (desktop)
                    previewCategory.textContent = category;

                    // Mettre à jour la barre de progression (desktop)
                    previewBar.style.width = Math.min(100, cursorPosition) + "%";

                    // Mettre à jour la position du curseur (desktop)
                    previewCursor.style.left = Math.min(100, cursorPosition) + "%";

                    // Mettre à jour le badge (desktop)
                    previewBadge.textContent = badgeText;
                    previewBadge.style.color = badgeColor;
                    previewBadge.style.backgroundColor = badgeBgColor;
                    previewBadge.style.borderColor = badgeBorderColor;

                    // Mettre à jour la version mobile si présente (anneau SVG)
                    const previewBMI_mobile = document.getElementById('preview-bmi-mobile');
                    const previewCategory_mobile = document.getElementById('preview-category-mobile');
                    const previewRingCircleMobile = document.getElementById('preview-ring-circle-mobile');
                    const previewRingTextMobile = document.getElementById('preview-ring-text-mobile');
                    const previewBadge_mobile = document.getElementById('preview-badge-mobile');

                    if (previewBMI_mobile) previewBMI_mobile.textContent = bmi.toFixed(1);
                    if (previewCategory_mobile) previewCategory_mobile.textContent = category;

                    if (previewRingCircleMobile) {
                        const r = parseFloat(previewRingCircleMobile.getAttribute('r')) || 28;
                        const circumference = 2 * Math.PI * r;
                        const pct = Math.min(100, Math.max(0, cursorPosition));
                        const offset = circumference * (1 - pct / 100);
                        previewRingCircleMobile.style.strokeDasharray = `${circumference.toFixed(2)}`;
                        previewRingCircleMobile.style.strokeDashoffset = `${offset.toFixed(2)}`;
                        // apply color to stroke by using inline stroke (fallback when gradient not ideal)
                        previewRingCircleMobile.style.filter = "";
                    }
                    if (previewRingTextMobile) previewRingTextMobile.textContent = '';
                    if (previewBadge_mobile) {
                        previewBadge_mobile.textContent = badgeText;
                        previewBadge_mobile.style.color = badgeColor;
                        previewBadge_mobile.style.backgroundColor = badgeBgColor;
                        previewBadge_mobile.style.borderColor = badgeBorderColor;
                    }
                } else {
                    previewBMI.textContent = "--";
                    previewCategory.textContent = "--";
                    previewBar.style.width = "0%";
                    previewCursor.style.left = "0%";
                    previewBadge.textContent = "Plage saine";
                    previewBadge.style.color = "#006e2f";
                    previewBadge.style.backgroundColor = "rgba(34, 197, 94, 0.25)";
                    previewBadge.style.borderColor = "rgba(0, 110, 47, 0.25)";

                    // mobile fallback (anneau à 0)
                    const previewBMI_mobile = document.getElementById('preview-bmi-mobile');
                    const previewCategory_mobile = document.getElementById('preview-category-mobile');
                    const previewRingCircleMobile = document.getElementById('preview-ring-circle-mobile');
                    const previewBadge_mobile = document.getElementById('preview-badge-mobile');

                    if (previewBMI_mobile) previewBMI_mobile.textContent = "--";
                    if (previewCategory_mobile) previewCategory_mobile.textContent = "--";
                    if (previewRingCircleMobile) {
                        const r = parseFloat(previewRingCircleMobile.getAttribute('r')) || 28;
                        const circumference = 2 * Math.PI * r;
                        previewRingCircleMobile.style.strokeDasharray = `${circumference.toFixed(2)}`;
                        previewRingCircleMobile.style.strokeDashoffset = `${circumference.toFixed(2)}`;
                    }
                    if (previewBadge_mobile) {
                        previewBadge_mobile.textContent = "Plage saine";
                        previewBadge_mobile.style.color = "#006e2f";
                        previewBadge_mobile.style.backgroundColor = "rgba(34, 197, 94, 0.25)";
                        previewBadge_mobile.style.borderColor = "rgba(0, 110, 47, 0.25)";
                    }
                }
            }

            // Listeners pour les inputs
            heightInput.addEventListener("input", calculateBMI);
            weightInput.addEventListener("input", calculateBMI);

            // Run once to initialize preview (in case inputs prefilled)
            calculateBMI();

            // Autofocus
            const firstInput = document.querySelector("form input");
            if (firstInput) {
                setTimeout(() => {
                    firstInput.focus();
                }, 300);
            }

            // Navigation avec Enter
            const formElements = Array.from(document.querySelectorAll("form input, form select"));
            formElements.forEach((el, index) => {
                el.addEventListener("keydown", function (e) {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        const next = formElements[index + 1];
                        if (next) {
                            next.focus();
                        }
                    }
                });

                        // Gestion des radio buttons objectif
                        const goalRadios = document.querySelectorAll(".goal-input");
                        const goalLabels = document.querySelectorAll("label[data-goal]");

                        goalRadios.forEach(radio => {
                            radio.addEventListener("change", function () {
                                // Retirer la classe active de tous les labels
                                goalLabels.forEach(label => {
                                    label.classList.remove("goal-active");
                                });

                                // Ajouter la classe active au label coché
                                if (this.checked) {
                                    const selectedLabel = document.querySelector(`label[data-goal="${this.value}"]`);
                                    if (selectedLabel) {
                                        selectedLabel.classList.add("goal-active");
                                    }
                                }
                            });
                        });

                        // Initialiser l'état actif au chargement (pour le radio pré-coché)
                        (function initGoalActive() {
                            let found = false;
                            goalRadios.forEach(radio => {
                                if (radio.checked) {
                                    const lbl = document.querySelector(`label[data-goal="${radio.value}"]`);
                                    if (lbl) lbl.classList.add('goal-active');
                                    found = true;
                                }
                            });
                            // If nothing checked, no-op
                            return found;
                        })();
            });
        });
    </script>
</body>

</html>