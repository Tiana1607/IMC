<?php
/**
 * Vue : Recommandations (Régimes + Activités)
 * 
 * Variables reçues du contrôleur :
 * - $diets (array) : Tableau des régimes recommandés avec titre, description, badge, image
 * - $activities (array) : Tableau des activités avec titre, time, kcal, image
 * - $imc (float|null) : Valeur IMC utilisateur
 * - $imcCategory (string|null) : Catégorie IMC (Sous-poids, Normal, etc.)
 * - $objectiveLabel (string) : Libellé de l'objectif (ex: "Perte de poids")
 */
?>

<style>
    .export:hover{
        color: grey;
    }
</style>

<div class="recommendations-container">
    <!-- Export Button -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem; gap: 1rem;">
        <a href="<?= site_url('export/recommendations') ?>" class="btn btn-outline-success btn-sm export" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
        </a>
    </div>

    <!-- Section Régimes Recommandés -->
    <section class="recommendations-section regime-section">
        <div class="section-header">
            <h2 class="section-title">Régimes recommandés</h2>
            <p class="section-subtitle">
                Pour votre objectif : <strong><?= htmlspecialchars($objectiveLabel ?? 'IMC idéal') ?></strong>
                <?php if ($imc !== null): ?>
                    | IMC actuel : <strong><?= number_format((float) $imc, 1) ?></strong> 
                    <span class="imc-badge <?= strtolower(str_replace(' ', '-', $imcCategory ?? '')) ?>">
                        <?= htmlspecialchars($imcCategory ?? 'N/A') ?>
                    </span>
                <?php endif; ?>
            </p>
        </div>

        <?php if (!empty($diets)): ?>
            <div class="regimes-grid">
                <?php foreach ($diets as $index => $diet): ?>
                    <div class="regime-card regime-card-<?= $index ?>">
                        <div class="regime-image">
                            <img 
                                src="<?= htmlspecialchars($diet['image'] ?? '') ?>" 
                                alt="<?= htmlspecialchars($diet['title'] ?? 'Régime') ?>"
                                loading="lazy"
                            />
                            <span class="regime-badge">
                                <?= htmlspecialchars($diet['badge'] ?? 'Recommandé') ?>
                            </span>
                        </div>
                        <div class="regime-content">
                            <h3 class="regime-title">
                                <?= htmlspecialchars($diet['title'] ?? 'Régime') ?>
                            </h3>
                            <p class="regime-description">
                                <?= htmlspecialchars($diet['description'] ?? 'Programme nutritionnel disponible.') ?>
                            </p>

                            <!-- Composition Nutritionnelle -->
                            <div class="regime-composition mb-3">
                                <div class="composition-row">
                                    <?php if ((int) ($diet['meat_percent'] ?? 0) > 0): ?>
                                        <span class="composition-item">
                                            <i class="bi bi-egg-fried" style="color: #8B4513;"></i>
                                            <span class="composition-value"><?= (int) ($diet['meat_percent'] ?? 0) ?>% Viande</span>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ((int) ($diet['fish_percent'] ?? 0) > 0): ?>
                                        <span class="composition-item">
                                            <i class="bi bi-fish" style="color: #4169E1;"></i>
                                            <span class="composition-value"><?= (int) ($diet['fish_percent'] ?? 0) ?>% Poisson</span>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ((int) ($diet['poultry_percent'] ?? 0) > 0): ?>
                                        <span class="composition-item">
                                            <i class="bi bi-egg" style="color: #FFD700;"></i>
                                            <span class="composition-value"><?= (int) ($diet['poultry_percent'] ?? 0) ?>% Volaille</span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Infos Supplémentaires -->
                            <div class="regime-details mb-3">
                                <?php if ($diet['calorie_target']): ?>
                                    <p class="detail-row">
                                        <strong>Calories :</strong> <?= (int) ($diet['calorie_target'] ?? 0) ?> kcal/jour
                                    </p>
                                <?php endif; ?>
                                <?php if ($diet['price_per_week']): ?>
                                    <p class="detail-row">
                                        <strong>Tarif :</strong> <?= number_format((float) ($diet['price_per_week'] ?? 0), 2) ?>€/semaine
                                    </p>
                                <?php endif; ?>
                            </div>

                            <!-- Bouton d'Action -->
                            <a href="<?= site_url('regimes') ?>" class="btn btn-sm btn-primary d-block">
                                <i class="bi bi-check-circle"></i> S'inscrire
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <p>Aucun régime recommandé pour le moment. Consultez notre catalogue complet.</p>
            </div>
        <?php endif; ?>
    </section>

    <!-- Section Activités Suggérées -->
    <section class="recommendations-section activities-section">
        <div class="section-header">
            <h2 class="section-title">Activités suggérées</h2>
            <p class="section-subtitle">
                Sélection personnalisée pour atteindre vos objectifs
            </p>
        </div>

        <?php if (!empty($activities)): ?>
            <div class="activities-grid">
                <?php foreach ($activities as $index => $activity): ?>
                    <div class="activity-card activity-card-<?= $index ?>">
                        <div class="activity-image">
                            <img 
                                src="<?= htmlspecialchars($activity['image'] ?? '') ?>" 
                                alt="<?= htmlspecialchars($activity['title'] ?? 'Activité') ?>"
                                loading="lazy"
                            />
                        </div>
                        <div class="activity-content">
                            <h3 class="activity-title">
                                <?= htmlspecialchars($activity['title'] ?? 'Activité') ?>
                            </h3>
                            <div class="activity-meta">
                                <span class="activity-time">
                                    <i class="bi bi-clock"></i>
                                    <?= htmlspecialchars($activity['time'] ?? '20 MIN') ?>
                                </span>
                                <span class="activity-kcal">
                                    <i class="bi bi-flame"></i>
                                    <?= (int) ($activity['kcal'] ?? 0) ?> kcal/h
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <p>Aucune activité recommandée pour le moment.</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<style>
    .recommendations-container {
        display: flex;
        flex-direction: column;
        gap: 3rem;
    }

    .recommendations-section {
        padding: 2rem;
        border-radius: 8px;
        background: var(--bs-light, #f8f9fa);
    }

    .section-header {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--bs-dark, #212529);
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        font-size: 0.95rem;
        color: var(--bs-secondary, #6c757d);
        margin: 0;
    }

    .imc-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .imc-badge.sous-poids {
        background-color: #cfe2ff;
        color: #084298;
    }

    .imc-badge.normal {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .imc-badge.surpoids {
        background-color: #fff3cd;
        color: #664d03;
    }

    .imc-badge.obésité {
        background-color: #f8d7da;
        color: #842029;
    }

    /* Régimes Grid */
    .regimes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .regime-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .regime-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .regime-image {
        position: relative;
        width: 100%;
        padding-bottom: 66.66%;
        overflow: hidden;
    }

    .regime-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .regime-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background-color: #0d6efd;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .regime-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }

    .regime-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--bs-dark);
        margin-bottom: 0.5rem;
    }

    .regime-description {
        font-size: 0.9rem;
        color: var(--bs-secondary);
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    /* Composition nutritionnelle */
    .regime-composition {
        padding: 1rem;
        background-color: #f0f2f5;
        border-radius: 6px;
    }

    .composition-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .composition-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--bs-dark);
    }

    .composition-value {
        font-size: 0.8rem;
    }

    /* Détails du régime */
    .regime-details {
        padding: 0.75rem;
        background-color: #fafbfc;
        border-radius: 6px;
        border-left: 3px solid #0d6efd;
    }

    .detail-row {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        color: var(--bs-dark);
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    /* Activités Grid */
    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .activity-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .activity-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .activity-image {
        position: relative;
        width: 100%;
        padding-bottom: 60%;
        overflow: hidden;
    }

    .activity-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .activity-content {
        padding: 1rem;
    }

    .activity-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--bs-dark);
        margin-bottom: 0.75rem;
    }

    .activity-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--bs-secondary);
    }

    .activity-time,
    .activity-kcal {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .recommendations-section {
            padding: 1.5rem;
        }

        .regimes-grid,
        .activities-grid {
            grid-template-columns: 1fr;
        }

        .section-title {
            font-size: 1.5rem;
        }
    }
</style>
