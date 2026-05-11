<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitalPath | Connexion</title>

    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/login.css') ?>">
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">
</head>

<body class="login-page">
    <main class="login-shell">
        <div class="container-fluid p-0">
            <div class="row g-0 min-vh-100">
                <section class="col-lg-6 d-none d-lg-flex login-hero align-items-end p-4 p-xl-5">
                    <div class="login-hero-overlay"></div>
                    <img src="<?= base_url('assets/images/image1.jpeg') ?>" alt="VitalPath santé" class="login-hero-image">
                    <div class="login-hero-content position-relative text-white">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-heart-pulse-fill fs-1 text-white"></i>
                            <span class="brand-mark">VitalPath</span>
                        </div>
                        <h1 class="display-5 fw-bold mb-3">Votre santé, plus simple à suivre.</h1>
                        <p class="lead mb-4 login-hero-text">
                            Connectez-vous pour accéder à votre tableau de bord, suivre votre IMC et garder le cap sur vos objectifs.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="hero-chip">
                                <i class="bi bi-shield-check me-2"></i> Données sécurisées
                            </div>
                            <div class="hero-chip">
                                <i class="bi bi-graph-up-arrow me-2"></i> Suivi IMC
                            </div>
                        </div>
                    </div>
                </section>

                <section class="col-12 col-lg-6 d-flex align-items-center justify-content-center login-panel">
                    <div class="login-card mx-auto">
                        <div class="d-lg-none text-center mb-4">
                            <a href="<?= site_url('/') ?>"
                                class="text-decoration-none d-inline-flex align-items-center gap-2 login-mobile-brand">
                                <i class="bi bi-heart-pulse-fill fs-1"></i>
                                <span class="login-brand">VitalPath</span>
                            </a>
                        </div>

                        <div class="mb-4 text-lg-start text-center">
                            <h1 class="login-title mb-2">Connexion</h1>
                            <p class="login-subtitle mb-0">Accédez à votre tableau de bord santé.</p>
                        </div>

                                <?php $errors = session()->getFlashdata('errors') ?? []; ?>

                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger border-0 rounded-3 mb-3">
                                        <?= esc(session()->getFlashdata('error')) ?>
                                    </div>
                                <?php endif; ?>

                        <form action="<?= site_url('auth/login') ?>" method="post" class="login-form">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-dark">Adresse email</label>
                                <div class="input-group input-group-lg login-input-group">
                                    <span class="input-group-text login-input-icon"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control login-input <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email"
                                        placeholder="nom@exemple.com" required autocomplete="email" value="admin@gmail.com">
                                </div>
                                <div class="login-feedback <?= isset($errors['email']) ? '' : 'd-none' ?>" id="email-feedback">
                                    <?= esc($errors['email'] ?? '') ?>
                                </div>
                                <small class="login-status d-none" id="email-status"></small>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="password" class="form-label fw-semibold text-dark mb-0">Mot de passe</label>
                                </div>
                                <div class="input-group input-group-lg login-input-group">
                                    <span class="input-group-text login-input-icon"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control login-input <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password"
                                        placeholder="••••••••" required autocomplete="current-password" value="AdminVital2026">
                                    <button class="btn login-eye-btn" type="button" id="togglePassword" aria-label="Afficher ou masquer le mot de passe">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="login-feedback <?= isset($errors['password']) ? '' : 'd-none' ?>" id="password-feedback">
                                    <?= esc($errors['password'] ?? '') ?>
                                </div>
                                <small class="login-status d-none" id="password-status"></small>
                            </div>

                            <div class="form-check login-check mb-4 mt-4">
                                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" checked>
                                <label class="form-check-label text-dark" for="remember">Ao @ USERS_MDP.md ny identifiants non hashé</label>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg rounded-pill w-100 login-primary-btn">
                                Se connecter
                            </button>

                            <div class="login-divider my-4">
                                <span>ou</span>
                            </div>

                            <a href="<?= site_url('/') ?>" class="btn btn-light btn-lg rounded-pill w-100 login-google-btn">
                                <i class="bi bi-info-circle me-2"></i>
                                En savoir plus
                            </a>
                        </form>

                        <div class="text-center mt-4 login-register-line">
                            <span>Vous n'avez pas encore de compte ?</span>
                            <a href="<?= site_url('auth/register/step1') ?>">Créer un compte</a>
                        </div>

                        <div class="text-center mt-4 d-lg-none login-mobile-note">
                            <i class="bi bi-shield-lock me-1"></i>
                            Connexion sécurisée
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const firstInput = document.getElementById('email');
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const emailInput = document.getElementById('email');
            const emailFeedback = document.getElementById('email-feedback');
            const emailStatus = document.getElementById('email-status');
            const passwordFeedback = document.getElementById('password-feedback');
            const passwordStatus = document.getElementById('password-status');
            const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
            const csrfTokenName = '<?= csrf_token() ?>';
            const formElements = Array.from(document.querySelectorAll('.login-form input'));
            let emailTimer;
            let passwordTimer;

            function getCsrfValue() {
                return csrfInput ? csrfInput.value : '';
            }

            function refreshCsrfToken(data) {
                if (!csrfInput || !data || !data.csrfHash) {
                    return;
                }

                csrfInput.value = data.csrfHash;
            }

            if (firstInput) {
                setTimeout(() => firstInput.focus(), 250);
            }

            formElements.forEach((element, index) => {
                element.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        const next = formElements[index + 1];
                        if (next) {
                            next.focus();
                        } else {
                            element.form?.submit();
                        }
                    }
                });
            });

            function setInvalid(input, feedbackEl, statusEl, message) {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                if (feedbackEl) {
                    feedbackEl.innerHTML = message;
                    feedbackEl.classList.remove('d-none');
                    feedbackEl.classList.add('d-block');
                    feedbackEl.classList.remove('is-valid');
                    feedbackEl.classList.add('is-invalid');
                    feedbackEl.classList.remove('pending');
                }
                if (statusEl) {
                    statusEl.innerHTML = '';
                    statusEl.classList.add('d-none');
                    statusEl.classList.remove('is-valid');
                    statusEl.classList.add('is-invalid');
                    statusEl.classList.remove('pending');
                }
            }

            function setValid(input, feedbackEl, statusEl, message) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                if (feedbackEl) {
                    feedbackEl.textContent = '';
                    feedbackEl.classList.add('d-none');
                    feedbackEl.classList.remove('d-block');
                    feedbackEl.classList.remove('is-invalid');
                    feedbackEl.classList.add('is-valid');
                    feedbackEl.classList.remove('pending');
                }
                if (statusEl) {
                    statusEl.innerHTML = message;
                    statusEl.classList.remove('d-none');
                    statusEl.classList.remove('is-invalid');
                    statusEl.classList.add('is-valid');
                    statusEl.classList.remove('pending');
                }
            }

            function setPending(statusEl, message) {
                if (!statusEl) {
                    return;
                }

                statusEl.innerHTML = message;
                statusEl.classList.remove('d-none', 'is-valid', 'is-invalid');
                statusEl.classList.add('pending');
            }

            if (emailInput) {
                emailInput.addEventListener('input', function () {
                    clearTimeout(emailTimer);
                    const email = emailInput.value.trim();

                    if (email === '') {
                        emailInput.classList.remove('is-valid', 'is-invalid');
                        if (emailFeedback) {
                            emailFeedback.textContent = '';
                            emailFeedback.classList.add('d-none');
                            emailFeedback.classList.remove('d-block');
                        }
                        if (emailStatus) emailStatus.classList.add('d-none');
                        return;
                    }

                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        setInvalid(emailInput, emailFeedback, emailStatus, 'Format email invalide');
                        return;
                    }

                    if (emailFeedback) {
                        emailFeedback.textContent = '';
                        emailFeedback.classList.add('d-none');
                        emailFeedback.classList.remove('d-block');
                    }
                    setPending(emailStatus, '<i class="bi bi-hourglass-split me-1"></i> Vérification du compte...');
                    emailInput.classList.remove('is-valid', 'is-invalid');

                    emailTimer = setTimeout(() => {
                        const payload = new URLSearchParams();
                        payload.append(csrfTokenName, getCsrfValue());
                        payload.append('email', email);

                        fetch("<?= site_url('auth/ajax_check_login_email') ?>", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: payload
                        })
                            .then(response => response.json())
                            .then(data => {
                                refreshCsrfToken(data);
                                if (data.exists) {
                                    setValid(emailInput, emailFeedback, emailStatus, '<i class="bi bi-check-circle-fill me-1"></i> ' + (data.message || 'Compte trouvé'));
                                } else {
                                    setInvalid(emailInput, emailFeedback, emailStatus, data.message || 'Aucun compte ne correspond à cette adresse.');
                                }
                            })
                            .catch(() => {
                                setInvalid(emailInput, emailFeedback, emailStatus, 'Erreur de vérification');
                            });
                    }, 450);
                });
            }

            function checkPassword() {
                clearTimeout(passwordTimer);
                const email = emailInput ? emailInput.value.trim() : '';
                const password = passwordInput ? passwordInput.value : '';

                if (password === '') {
                    passwordInput.classList.remove('is-valid', 'is-invalid');
                    if (passwordFeedback) {
                        passwordFeedback.textContent = '';
                        passwordFeedback.classList.add('d-none');
                        passwordFeedback.classList.remove('d-block');
                    }
                    if (passwordStatus) passwordStatus.classList.add('d-none');
                    return;
                }

                if (email === '' || !emailInput.classList.contains('is-valid')) {
                    setInvalid(passwordInput, passwordFeedback, passwordStatus, 'Commencez par valider votre email.');
                    return;
                }

                if (passwordFeedback) {
                    passwordFeedback.textContent = '';
                    passwordFeedback.classList.add('d-none');
                    passwordFeedback.classList.remove('d-block');
                }
                setPending(passwordStatus, '<i class="bi bi-hourglass-split me-1"></i> Vérification du mot de passe...');
                passwordInput.classList.remove('is-valid', 'is-invalid');

                passwordTimer = setTimeout(() => {
                    const payload = new URLSearchParams();
                    payload.append(csrfTokenName, getCsrfValue());
                    payload.append('email', email);
                    payload.append('password', password);

                    fetch("<?= site_url('auth/ajax_check_login_password') ?>", {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: payload
                    })
                        .then(response => response.json())
                        .then(data => {
                            refreshCsrfToken(data);
                            if (data.valid) {
                                setValid(passwordInput, passwordFeedback, passwordStatus, '<i class="bi bi-check-circle-fill me-1"></i> ' + (data.message || 'Mot de passe correct'));
                            } else {
                                setInvalid(passwordInput, passwordFeedback, passwordStatus, data.message || 'Mot de passe incorrect');
                            }
                        })
                        .catch(() => {
                            setInvalid(passwordInput, passwordFeedback, passwordStatus, 'Erreur de vérification');
                        });
                }, 450);
            }

            if (passwordInput) {
                passwordInput.addEventListener('input', checkPassword);
            }

            if (emailInput) {
                emailInput.addEventListener('blur', function () {
                    if (emailInput.value.trim() !== '' && !emailInput.classList.contains('is-valid')) {
                        emailInput.dispatchEvent(new Event('input'));
                    }
                });
            }

            if (window.innerWidth < 768 && firstInput) {
                window.addEventListener('load', () => {
                    firstInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    const isHidden = passwordInput.type === 'password';
                    passwordInput.type = isHidden ? 'text' : 'password';
                    this.innerHTML = isHidden ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
                });
            }
        });
    </script>
</body>

</html>