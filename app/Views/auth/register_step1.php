<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitalPath | Inscription</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/register_step1.css') ?>">
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">
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
                    <span class="d-none d-md-inline small text-uppercase fw-semibold text-success">Création du
                        compte</span>
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
                        <div
                            class="badge rounded-pill text-bg-success-subtle text-success border border-success-subtle mb-4 px-3 py-2">
                            <i class="bi bi-shield-check me-1"></i>
                            Données sécurisées
                        </div>

                        <h1 class="fw-bold text-dark mb-3">Votre parcours santé commence ici.</h1>
                        <p class="lead text-secondary mb-4">
                            Inscrivez-vous pour calculer votre IMC, choisir un objectif et recevoir un régime
                            adapté.
                        </p>

                        <div class="auth-visual-media mb-4">
                            <img src="<?= base_url('assets/images/image1.jpeg') ?>" alt="VitalPath">
                        </div>

                        <div class="d-flex align-items-center gap-3 text-secondary small flex-wrap">
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                IMC
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                Régime
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                Activités
                            </div>
                        </div>
                    </div>
                </section>

                <section class="col-lg-6 d-flex align-items-center justify-content-center p-3 p-md-4 p-xl-5 bg-white">
                    <div class="auth-card auth-copy py-2 py-lg-3">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span class="section-label text-success">Étape 1 sur 2</span>
                                <span class="small text-secondary">Profil personnel</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 50%;"></div>
                            </div>
                        </div>

                        <h2 class="fw-bold mb-2 text-dark">Créer mon compte</h2>
                        <p class="text-secondary mb-4">Renseigne tes infos de base pour continuer.</p>

                        <form action="<?= site_url('auth/register/step1') ?>" method="post" class="needs-validation"
                            novalidate>
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="full_name" class="form-label fw-semibold">Nom complet</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                        placeholder="Ton nom d'utilisateur" required>
                                </div>
                                <div class="invalid-feedback d-none"></div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control email-input" id="email" name="email"
                                        placeholder="nom@exemple.com" required>
                                    <span class="email-icon-feedback d-none"></span>
                                </div>
                                <div class="invalid-feedback d-none" id="email-feedback"></div>
                                <small class="email-status d-none" id="email-status"></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Genre</label>
                                <div class="btn-group w-100 btn-gender" role="group" aria-label="Genre">
                                    <input type="radio" class="btn-check" name="gender" id="gender_m" value="male"
                                        autocomplete="off">
                                    <label class="btn btn-outline-secondary py-2" for="gender_m"><i
                                            class="bi bi-gender-male me-1"></i> Homme</label>

                                    <input type="radio" class="btn-check" name="gender" id="gender_f" value="female"
                                        autocomplete="off">
                                    <label class="btn btn-outline-secondary py-2" for="gender_f"><i
                                            class="bi bi-gender-female me-1"></i> Femme</label>

                                    <input type="radio" class="btn-check" name="gender" id="gender_o" value="other"
                                        autocomplete="off">
                                    <label class="btn btn-outline-secondary py-2" for="gender_o"><i
                                            class="bi bi-gender-trans me-1"></i> Autre</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Crée ton mot de passe" required>
                                    <span class="email-icon-feedback d-none" id="password-icon-feedback"></span>
                                </div>
                                <div class="invalid-feedback d-none" id="password-feedback"></div>
                                <small class="email-status d-none" id="password-status"></small>
                            </div>

                            <div class="alert alert-light border d-flex gap-3 align-items-start mb-4">
                                <i class="bi bi-shield-lock text-success fs-5"></i>
                                <div class="small text-secondary">
                                    Tes données servent uniquement à calculer l’IMC et proposer un régime adapté.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success rounded-pill w-100 py-3 fw-semibold" disabled>
                                Continuer vers l’étape 2
                                <i class="bi bi-arrow-right ms-2"></i>
                            </button>

                            <div class="text-center mt-3 small text-secondary">
                                Déjà un compte ?
                                <a href="<?= site_url('auth/login') ?>" class="text-success fw-semibold text-decoration-none auth-connect-link">Se
                                    connecter</a>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        const csrfFieldName = "<?= csrf_token() ?>";
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // 1. Autofocus sur le premier champ visible
            const firstInput = document.querySelector("form input, form select, form textarea");

            if (firstInput) {
                setTimeout(() => {
                    firstInput.focus();
                }, 300);
            }

            // 2. Enter = focus next input (UX mobile + desktop)
            const formElements = Array.from(document.querySelectorAll("form input, form select, form textarea"));

            formElements.forEach((el, index) => {
                el.addEventListener("keydown", function (e) {
                    if (e.key === "Enter") {
                        e.preventDefault();

                        const next = formElements[index + 1];
                        if (next) {
                            next.focus();
                        } else {
                            el.form?.submit();
                        }
                    }
                });
            });

            // 3. Auto focus visible input on mobile (clavier propre)
            window.addEventListener("load", () => {
                if (window.innerWidth < 768 && firstInput) {
                    firstInput.scrollIntoView({ behavior: "smooth", block: "center" });
                }
            });

        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const nameInput = document.getElementById("full_name");
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
            const emailFeedback = document.getElementById("email-feedback");
            const emailStatus = document.getElementById("email-status");
            const emailIconFeedback = document.querySelector(".email-icon-feedback");
            const passwordFeedback = document.getElementById("password-feedback");
            const passwordStatus = document.getElementById("password-status");
            const passwordIconFeedback = document.getElementById("password-icon-feedback");
            const genderInputs = Array.from(document.querySelectorAll('input[name="gender"]'));
            const submitButton = document.querySelector('button[type="submit"]');
            const csrfInput = document.querySelector(`input[name="${csrfFieldName}"]`);

            // Validation du nom
            nameInput.addEventListener("input", function () {
                if (nameInput.value.trim().length < 3) {
                    setInvalid(nameInput, "Nom trop court (min 3 caractères)");
                } else {
                    setValid(nameInput);
                }
                updateSubmitState();
            });

            let emailTimer;
            let isEmailChecking = false;

            let passwordTimer;
            let isPasswordChecking = false;

            emailInput.addEventListener("input", function () {
                clearTimeout(emailTimer);

                const email = emailInput.value.trim();

                // Vider l'email = reset
                if (email === "") {
                    emailInput.classList.remove("is-valid", "is-invalid");
                    emailFeedback.classList.add("d-none");
                    emailStatus.classList.add("d-none");
                    emailIconFeedback.classList.add("d-none");
                    updateSubmitState();
                    return;
                }

                // Validation format
                if (!validateEmail(email)) {
                    setEmailInvalid(emailInput, "Format email invalide");
                    updateSubmitState();
                    return;
                }

                // Vérification sur le serveur
                setEmailPending(emailInput);
                isEmailChecking = true;

                emailTimer = setTimeout(() => {
                    const formData = new FormData();
                    formData.append('email', email);
                    formData.append(csrfFieldName, csrfInput.value);

                    fetch("<?= site_url('auth/ajax_check_email') ?>", {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: formData
                    })
                        .then(res => res.json())
                        .then(data => {
                            isEmailChecking = false;

                            if (data.csrfHash && csrfInput) {
                                csrfInput.value = data.csrfHash;
                            }

                            if (data.exists) {
                                setEmailInvalid(emailInput, "Email déjà utilisé");
                            } else {
                                setEmailValid(emailInput);
                            }
                            updateSubmitState();
                        })
                        .catch((err) => {
                            isEmailChecking = false;
                            setEmailInvalid(emailInput, "Erreur de vérification");
                            updateSubmitState();
                        });
                }, 500);
            });

            passwordInput.addEventListener("input", function () {
                clearTimeout(passwordTimer);

                const password = passwordInput.value;

                if (password === "") {
                    passwordInput.classList.remove("is-valid", "is-invalid");
                    passwordFeedback.classList.add("d-none");
                    passwordStatus.classList.add("d-none");
                    passwordIconFeedback.classList.add("d-none");
                    updateSubmitState();
                    return;
                }

                if (password.length < 8) {
                    setPasswordInvalid(passwordInput, "Le mot de passe doit contenir au moins 8 caractères");
                    updateSubmitState();
                    return;
                }

                setPasswordValid(passwordInput);
                isPasswordChecking = false;
                updateSubmitState();
            });

            // Focus out - validation finale
            emailInput.addEventListener("blur", function () {
                const email = emailInput.value.trim();
                if (email && !emailInput.classList.contains("is-valid")) {
                    if (!validateEmail(email)) {
                        setEmailInvalid(emailInput, "Format email invalide");
                    }
                }
            });

            function setEmailInvalid(input, message) {
                input.classList.remove("is-valid");
                input.classList.add("is-invalid");
                emailFeedback.textContent = message;
                emailFeedback.classList.remove("d-none");
                emailStatus.classList.add("d-none");
                emailIconFeedback.innerHTML = "<i class='bi bi-exclamation-circle-fill'></i>";
                emailIconFeedback.classList.remove("valid", "pending");
                emailIconFeedback.classList.add("invalid", "d-flex");
                updateSubmitState();
            }

            function setEmailValid(input) {
                input.classList.remove("is-invalid");
                input.classList.add("is-valid");
                emailFeedback.classList.add("d-none");
                emailStatus.textContent = "Email disponible ✓";
                emailStatus.classList.remove("d-none", "pending");
                emailIconFeedback.innerHTML = "<i class='bi bi-check-circle-fill'></i>";
                emailIconFeedback.classList.remove("invalid", "pending");
                emailIconFeedback.classList.add("valid", "d-flex");
                updateSubmitState();
            }

            function setEmailPending(input) {
                input.classList.remove("is-valid", "is-invalid");
                emailFeedback.classList.add("d-none");
                emailStatus.textContent = "Vérification en cours...";
                emailStatus.classList.remove("d-none");
                emailStatus.classList.add("pending");
                emailIconFeedback.innerHTML = "<i class='bi bi-hourglass-split'></i>";
                emailIconFeedback.classList.remove("valid", "invalid");
                emailIconFeedback.classList.add("pending", "d-flex");
                updateSubmitState();
            }

            function setPasswordInvalid(input, message) {
                input.classList.remove("is-valid");
                input.classList.add("is-invalid");
                passwordFeedback.textContent = message;
                passwordFeedback.classList.remove("d-none");
                passwordStatus.classList.add("d-none");
                passwordIconFeedback.innerHTML = "<i class='bi bi-exclamation-circle-fill'></i>";
                passwordIconFeedback.classList.remove("valid", "pending");
                passwordIconFeedback.classList.add("invalid", "d-flex");
                updateSubmitState();
            }

            function setPasswordValid(input, message) {
                input.classList.remove("is-invalid");
                input.classList.add("is-valid");
                passwordFeedback.classList.add("d-none");
                passwordStatus.textContent = message;
                passwordStatus.classList.remove("d-none", "pending");
                passwordIconFeedback.innerHTML = "<i class='bi bi-check-circle-fill'></i>";
                passwordIconFeedback.classList.remove("invalid", "pending");
                passwordIconFeedback.classList.add("valid", "d-flex");
                updateSubmitState();
            }

            function setPasswordPending(input) {
                input.classList.remove("is-valid", "is-invalid");
                passwordFeedback.classList.add("d-none");
                passwordStatus.textContent = "Vérification en cours...";
                passwordStatus.classList.remove("d-none");
                passwordStatus.classList.add("pending");
                passwordIconFeedback.innerHTML = "<i class='bi bi-hourglass-split'></i>";
                passwordIconFeedback.classList.remove("valid", "invalid");
                passwordIconFeedback.classList.add("pending", "d-flex");
                updateSubmitState();
            }

            function setInvalid(input, message) {
                input.classList.remove("is-valid");
                input.classList.add("is-invalid");
                const feedback = input.closest(".mb-3").querySelector(".invalid-feedback");
                if (feedback) {
                    feedback.textContent = message;
                    feedback.classList.remove("d-none");
                }
            }

            function setValid(input) {
                input.classList.remove("is-invalid");
                input.classList.add("is-valid");
                const feedback = input.closest(".mb-3").querySelector(".invalid-feedback");
                if (feedback) {
                    feedback.textContent = "";
                    feedback.classList.add("d-none");
                }
            }

            function validateEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function isGenderSelected() {
                return genderInputs.some(input => input.checked);
            }

            function isFormReady() {
                return nameInput.classList.contains("is-valid")
                    && emailInput.classList.contains("is-valid")
                    && passwordInput.classList.contains("is-valid")
                    && isGenderSelected()
                    && !isEmailChecking
                    && !isPasswordChecking;
            }

            function updateSubmitState() {
                submitButton.disabled = !isFormReady();
            }

            genderInputs.forEach(input => input.addEventListener("change", updateSubmitState));

            updateSubmitState();

        });
    </script>
</body>

</html>