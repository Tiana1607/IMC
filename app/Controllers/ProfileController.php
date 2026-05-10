<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ImmediateCalculationHelper;
use App\Models\User;

class ProfileController extends BaseController
{
    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Afficher la vue d'édition du profil
     */
    public function edit()
    {
        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Utilisateur introuvable.');
        }

        $objectiveOptions = [
            'augmenter_poids' => 'Prendre du poids',
            'reduire_poids' => 'Perte de poids',
            'imc_ideal' => 'IMC idéal',
        ];

        return view('profile/edit', [
            'user' => $user,
            'objectiveOptions' => $objectiveOptions,
        ]);
    }

    /**
     * Mettre à jour le profil utilisateur
     * Recalcule l'IMC si le poids ou la taille changent
     */
    public function update()
    {
        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Utilisateur introuvable.');
        }

        $height_cm = (float) $this->request->getPost('height');
        $weight_kg = (float) $this->request->getPost('weight');
        $age = (int) $this->request->getPost('age');
        $objective = trim($this->request->getPost('objective'));
        $errors = [];

        // Validation
        if ($height_cm < 100 || $height_cm > 250) {
            $errors['height'] = 'La taille doit être comprise entre 100 et 250 cm.';
        }

        if ($weight_kg < 20 || $weight_kg > 300) {
            $errors['weight'] = 'Le poids doit être compris entre 20 et 300 kg.';
        }

        if ($age < 13 || $age > 120) {
            $errors['age'] = 'L\'age doit etre compris entre 13 et 120 ans.';
        }

        if (!in_array($objective, ['augmenter_poids', 'reduire_poids', 'imc_ideal'], true)) {
            $errors['objective'] = 'Veuillez choisir un objectif valide.';
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->with('errors', $errors)
                ->withInput();
        }

        // Recalculer l'IMC avec le Helper
        $imc = ImmediateCalculationHelper::calculateIMC($weight_kg, $height_cm);
        $imcCategory = ImmediateCalculationHelper::getIMCCategory($imc);

        // Préparer les données de mise à jour
        $updateData = [
            'height_cm' => $height_cm,
            'weight_kg' => $weight_kg,
            'age' => $age,
            'objective' => $objective,
            'imc_value' => $imc,
            'imc_category' => $imcCategory,
        ];

        // Mettre à jour l'utilisateur
        if (!$this->userModel->update($userId, $updateData)) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du profil.')
                ->withInput();
        }

        return redirect()->to('/dashboard')
            ->with('success', 'Profil mis à jour avec succès! IMC recalculé.');
    }

    /**
     * Afficher le profil complet (page informative)
     */
    public function view()
    {
        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/auth/login')->with('error', 'Utilisateur introuvable.');
        }

        $imc = $user['imc_value'] ?? ImmediateCalculationHelper::calculateIMC(
            (float) ($user['weight_kg'] ?? 0),
            (float) ($user['height_cm'] ?? 0)
        );

        $imcCategory = $user['imc_category'] ?? ImmediateCalculationHelper::getIMCCategory($imc);

        $objectiveLabels = [
            'augmenter_poids' => 'Prendre du poids',
            'reduire_poids' => 'Perte de poids',
            'imc_ideal' => 'IMC idéal',
        ];

        return view('profile/view', [
            'user' => $user,
            'imc' => $imc,
            'imcCategory' => $imcCategory,
            'objectiveLabel' => $objectiveLabels[$user['objective'] ?? 'imc_ideal'] ?? ucfirst(str_replace('_', ' ', $user['objective'] ?? '')),
        ]);
    }
}
