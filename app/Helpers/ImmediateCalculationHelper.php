<?php

namespace App\Helpers;

/**
 * Helper pour les calculs immédiats : IMC, catégories, etc.
 * Peut être utilisé partout dans l'application.
 */
class ImmediateCalculationHelper
{
    /**
     * Calculer l'IMC (Indice de Masse Corporelle)
     * Formule : IMC = poids (kg) / (taille (m))²
     *
     * @param float $weight_kg Poids en kilogrammes
     * @param float $height_cm Taille en centimètres
     * @return float|null IMC arrondi à 2 décimales, ou null si données invalides
     */
    public static function calculateIMC($weight_kg, $height_cm)
    {
        if ($height_cm <= 0 || $weight_kg <= 0) {
            return null;
        }

        $height_m = $height_cm / 100;
        $imc = $weight_kg / ($height_m * $height_m);

        return round($imc, 2);
    }

    /**
     * Retourner la catégorie IMC basée sur la valeur IMC
     * 
     * @param float|null $imc Valeur IMC
     * @return string|null Catégorie : "Sous-poids", "Normal", "Surpoids", "Obésité", ou null
     */
    public static function getIMCCategory($imc)
    {
        if ($imc === null) {
            return null;
        }

        if ($imc < 18.5) {
            return 'Sous-poids';
        }

        if ($imc >= 18.5 && $imc < 25) {
            return 'Normal';
        }

        if ($imc >= 25 && $imc < 30) {
            return 'Surpoids';
        }

        return 'Obésité';
    }

    /**
     * Retourner un nombre de recommandations basées sur objectif + IMC
     * Applique la logique métier :
     *  - Objectif = "Gains" ET IMC < 25 → régimes +calories, activités force
     *  - Objectif = "Perte" ET IMC > 25 → régimes -calories, activités cardio
     *  - Objectif = "Idéal" → régimes équilibrés
     *
     * @param string $objective Objectif utilisateur: 'augmenter_poids', 'reduire_poids', 'imc_ideal'
     * @param float|null $imc Valeur IMC actuelle
     * @return array Tableau avec clés 'regime_preference' et 'activity_preference'
     */
    public static function getRecommendationPreferences($objective, $imc = null)
    {
        $preferences = [
            'regime_preference' => 'balanced',
            'activity_preference' => 'balanced',
        ];

        // Objectif "Gains" (prendre du poids)
        if ($objective === 'augmenter_poids' && ($imc === null || $imc < 25)) {
            $preferences['regime_preference'] = 'high_calories';
            $preferences['activity_preference'] = 'strength';
        }

        // Objectif "Perte" (réduire poids)
        if ($objective === 'reduire_poids' || ($imc !== null && $imc >= 25)) {
            $preferences['regime_preference'] = 'low_calories';
            $preferences['activity_preference'] = 'cardio';
        }

        // Objectif "Idéal" → régimes équilibrés
        if ($objective === 'imc_ideal') {
            $preferences['regime_preference'] = 'balanced';
            $preferences['activity_preference'] = 'balanced';
        }

        return $preferences;
    }

    /**
     * Classer les régimes selon les préférences
     *
     * @param array $regimes Tableau de régimes avec clés 'calorie_target'
     * @param string $preference 'high_calories', 'low_calories', ou 'balanced'
     * @return array Régimes triés
     */
    public static function sortRegimesByPreference(array $regimes, $preference = 'balanced')
    {
        $sorted = $regimes;

        if ($preference === 'low_calories') {
            // Tri croissant par calories (moins de calories en premier)
            usort($sorted, fn($a, $b) => ((int) ($a['calorie_target'] ?? 0)) <=> ((int) ($b['calorie_target'] ?? 0)));
        } elseif ($preference === 'high_calories') {
            // Tri décroissant par calories (plus de calories en premier)
            usort($sorted, fn($a, $b) => ((int) ($b['calorie_target'] ?? 0)) <=> ((int) ($a['calorie_target'] ?? 0)));
        } else {
            // Tri équilibré : proche de 2200 kcal
            usort($sorted, fn($a, $b) => abs((int) ($a['calorie_target'] ?? 0) - 2200) <=> abs((int) ($b['calorie_target'] ?? 0) - 2200));
        }

        return $sorted;
    }

    /**
     * Classer les activités selon les préférences
     *
     * @param array $activities Tableau d'activités avec clés 'calories_per_hour'
     * @param string $preference 'cardio', 'strength', ou 'balanced'
     * @return array Activités triées
     */
    public static function sortActivitiesByPreference(array $activities, $preference = 'balanced')
    {
        $sorted = $activities;

        if ($preference === 'cardio') {
            // Tri décroissant par calories (plus de calories en premier = plus intense)
            usort($sorted, fn($a, $b) => ((int) ($b['calories_per_hour'] ?? 0)) <=> ((int) ($a['calories_per_hour'] ?? 0)));
        } elseif ($preference === 'strength') {
            // Tri croissant par calories (moins de calories mais plus de force = musculation)
            usort($sorted, fn($a, $b) => ((int) ($a['calories_per_hour'] ?? 0)) <=> ((int) ($b['calories_per_hour'] ?? 0)));
        } else {
            // Tri équilibré : proche de 250 kcal/h
            usort($sorted, fn($a, $b) => abs((int) ($a['calories_per_hour'] ?? 0) - 250) <=> abs((int) ($b['calories_per_hour'] ?? 0) - 250));
        }

        return $sorted;
    }
}
