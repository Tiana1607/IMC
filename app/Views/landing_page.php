<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitalPath | Régime & IMC</title>

    <!-- Bootstrap LOCAL -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/icons/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/landing.css') ?>">
    <link rel="icon" href="<?= base_url('assets/images/heart-pulse-fill.png') ?>" type="image/png">

</head>

<body>

    <!-- HEADER -->
    <header class="topbar fixed-top d-flex align-items-center">

        <div class="container-xxl px-4">

            <div class="d-flex align-items-center justify-content-between gap-3">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-heart-pulse-fill text-success fs-2"></i>

                    <span class="brand">
                        VitalPath
                    </span>

                </div>

                <!-- <nav class="d-none d-md-flex align-items-center gap-2 flex-grow-1 justify-content-center">

                    <a href="#" class="nav-link-custom nav-active">
                        Plan
                    </a>

                    <a href="#" class="nav-link-custom">
                        Metrics
                    </a>

                    <a href="#" class="nav-link-custom">
                        Goals
                    </a>

                </nav> -->

                <div class="d-flex align-items-center gap-2">

                    <a href="<?= site_url('auth/login') ?>" class="login-btn d-none d-sm-block text-decoration-none">
                        Connexion
                    </a>

                    <a href="<?= site_url('auth/register/step1') ?>" class="signup-btn text-decoration-none">
                        Inscription
                    </a>

                </div>

            </div>

        </div>

    </header>

    <!-- HERO -->
    <section class="hero-section" id="plan">

        <div class="container-xxl px-4 text-start text-md-center position-relative">

            <div class="hero-badge">

                <i class="bi bi-stars" style="font-size:18px;"></i>

                IMC ET RÉGIME

            </div>

            <h1 class="hero-title">
                Votre régime
                <span>sur mesure</span>
            </h1>

            <p class="hero-text">
                Entrez vos infos, calculez votre IMC et obtenez un régime adapté à vos objectifs.
            </p>

            <div
                class="hero-buttons d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-center gap-3">

                <a href="<?= site_url('auth/register/step1') ?>" class="btn-main d-flex align-items-center justify-content-center text-decoration-none">

                    Inscription

                    <i class="bi bi-arrow-right ms-2"></i>

                </a>

                <a href="<?= site_url('auth/login') ?>" class="btn-alt text-decoration-none">
                    Connexion
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>

            </div>

            <!-- IMAGE -->
            <div class="hero-image-wrapper">

                <div class="hero-image-container">

                    <img class="hero-image" src="<?= base_url('assets/images/image.jpg') ?>">

                </div>

                <!-- FLOAT LEFT -->
                <div class="floating-card floating-left d-none d-lg-block">

                    <div class="d-flex align-items-center gap-3">

                        <div class="floating-icon bg-success text-white">

                            <i class="bi bi-graph-up"></i>

                        </div>

                        <div class="text-start">

                            <div class="floating-label">
                                Progression du jour
                            </div>

                            <p class="floating-value text-success">
                                +12% d’énergie
                            </p>

                        </div>

                    </div>

                </div>

                <!-- FLOAT RIGHT -->
                <div class="floating-card floating-right d-none d-lg-block">

                    <div class="d-flex align-items-center gap-3">

                        <div class="floating-icon" style="background:#a4f1b2;">

                            <i class="bi bi-egg-fried"></i>

                        </div>

                        <div class="text-start">

                            <div class="floating-label">
                                Macros optimisés
                            </div>

                            <p class="floating-value" style="color:#1f6c3a;">
                                Priorité protéines
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="blur-left"></div>
        <div class="blur-right"></div>

    </section>

    <!-- SOCIAL -->
    <section class="stats-section" id="metrics">

        <div class="container-xxl px-4">

            <div class="d-md-none text-center mb-4">
                <div class="small text-uppercase fw-semibold mb-3" style="letter-spacing:.18em;color:var(--muted);">
                    Outils santé simples
                </div>
                <div class="d-flex justify-content-around align-items-center opacity-75">
                    <i class="bi bi-hospital fs-3"></i>
                    <i class="bi bi-shield-check fs-3"></i>
                    <i class="bi bi-heart fs-3"></i>
                    <i class="bi bi-flower1 fs-3"></i>
                </div>
            </div>

            <div class="row text-center d-none d-md-flex">

                <div class="col-md-4 mb-5 mb-md-0">

                    <div class="stat-number text-success">
                        98%
                    </div>

                    <div class="stat-label">
                        IMC CALCULÉ
                    </div>

                </div>

                <div class="col-md-4 mb-5 mb-md-0">

                    <div class="stat-number" style="color:#1f6c3a;">
                        2.4M+
                    </div>

                    <div class="stat-label">
                        OBJECTIFS
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="stat-number" style="color:#9e4036;">
                        4.9/5
                    </div>

                    <div class="stat-label">
                        APP STORE RATING
                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- FEATURES -->
    <section class="features-section" id="goals">

        <div class="container-xxl px-4">

            <div class="text-center mb-5">

                <h2 class="section-title mb-4">
                    Des outils simples et précis
                </h2>

                <p class="section-text">
                    Tout ce qu’il faut pour suivre votre IMC, vos régimes et vos objectifs.
                </p>

            </div>

            <div class="features-grid">

                <!-- BMI -->
                <div class="span-8">

                    <div class="feature-card">

                        <div class="feature-icon" style="background:#22c55e20;color:#006e2f;">

                            <i class="bi bi-speedometer2 fs-2"></i>

                        </div>

                        <h3 class="feature-title">
                            Suivi IMC
                        </h3>

                        <p class="feature-text">
                            Suivez votre IMC et vos progrès en un coup d’œil.
                        </p>

                    </div>

                </div>

                <!-- DIETS -->
                <div class="span-4">

                    <div class="feature-card" style="background:#a4f1b2;">

                        <div class="feature-icon bg-white bg-opacity-50">

                            <i class="bi bi-basket2 fs-2"></i>

                        </div>

                        <h3 class="feature-title">
                            Régimes adaptés
                        </h3>

                        <p class="feature-text text-dark">
                            Des menus selon votre objectif.
                        </p>

                    </div>

                </div>

                <!-- ACTIVITY -->
                <div class="span-4">

                    <div class="feature-card">

                        <div class="feature-icon" style="background:#ff8b7c;">

                            <i class="bi bi-lightning-charge-fill fs-2"></i>

                        </div>

                        <h3 class="feature-title">
                            Activités sportives
                        </h3>

                        <p class="feature-text">
                            Bougez selon votre programme.
                        </p>

                    </div>

                </div>

                <!-- ANALYTICS -->
                <div class="span-8">

                    <div class="feature-card feature-dark">

                        <div class="row align-items-center h-100">

                            <div class="col-lg-8">

                                <h3 class="feature-title mb-4">
                                    Suivi clair
                                </h3>

                                <p class="feature-text mb-5">
                                    Visualisez vos résultats simplement.
                                </p>

                                <div class="progress-custom">
                                    <div></div>
                                </div>

                            </div>

                            <div class="col-lg-4 text-center mt-5 mt-lg-0">

                                <div class="analytics-circle mx-auto">

                                    <h2 class="fw-bold text-success m-0">
                                        75%
                                    </h2>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- CTA -->
    <section class="cta-section text-center">

        <div class="container-xxl px-4">

            <h2 class="cta-title mb-4">
                Prêt à commencer ?
            </h2>

            <p class="cta-text mb-5">
                Créez votre profil et obtenez un régime adapté rapidement.
            </p>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">

                <a href="<?= site_url('auth/register/step1') ?>" class="btn btn-light rounded-pill px-5 py-3 fw-semibold text-decoration-none">
                    Commencer
                </a>

                <a href="<?= site_url('auth/login') ?>" class="btn rounded-pill px-5 py-3 fw-semibold text-white border border-light text-decoration-none">
                    En savoir plus
                </a>

            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <footer class="footer-section" id="footer">

        <div class="container-xxl px-4">

            <div class="footer-grid">

                <div>

                    <div class="d-flex align-items-center gap-2 mb-4">

                        <i class="bi bi-heart-pulse-fill text-success fs-2"></i>

                        <span class="brand">
                            VitalPath
                        </span>

                    </div>

                    <p style="color:var(--muted);max-width:320px;">
                        Une application simple pour gérer IMC, régime et objectifs.
                    </p>

                </div>

                <div>

                    <h5 class="footer-title">
                        Produit
                    </h5>

                    <ul class="footer-links">

                        <li><a href="<?= site_url('auth/login') ?>">Suivi IMC</a></li>
                        <li><a href="<?= site_url('auth/login') ?>">Régimes</a></li>
                        <li><a href="<?= site_url('auth/login') ?>">Activités</a></li>
                        <li><a href="#">Application mobile</a></li>

                    </ul>

                </div>

                <div>

                    <h5 class="footer-title">
                        Projet
                    </h5>

                    <ul class="footer-links">

                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Objectifs</a></li>
                        <li><a href="#">Technos</a></li>
                        <li><a href="#">Blog</a></li>

                    </ul>

                </div>

                <div>

                    <h5 class="footer-title">
                        Aide
                    </h5>

                    <ul class="footer-links">

                        <li><a href="#">Aide</a></li>
                        <li><a href="#">Confidentialité</a></li>
                        <li><a href="#">Conditions</a></li>
                        <li><a href="#">Sécurité</a></li>

                    </ul>

                </div>

            </div>

            <div class="footer-bottom">
                © 2026 VitalPath (4286-4153-1944). All rights reserved.
            </div>

        </div>

    </footer>

    <!-- Bootstrap -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        // Animation légère au chargement des éléments du footer
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.footer-links li').forEach((li, index) => {
                li.style.opacity = '0';
                li.style.transform = 'translateY(10px)';
                li.style.transition = `opacity 0.4s ease ${index * 0.05}s, transform 0.4s ease ${index * 0.05}s`;
                observer.observe(li);
            });
        });
    </script>

</body>

</html>