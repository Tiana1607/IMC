<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Helpers\ImmediateCalculationHelper;
use App\Models\User;

class Auth extends BaseController
{
	protected $userModel;
	protected $session;

	public function __construct()
	{
		$this->userModel = new User();
		$this->session = session();
	}

	public function index()
	{
		return redirect()->to('/dashboard');
	}

	public function login()
	{
		if ($this->request->is('get')) {
			return view('auth/login');
		}

		$email = trim((string) $this->request->getPost('email'));
		$password = (string) $this->request->getPost('password');
		$remember = (bool) $this->request->getPost('remember');
		$errors = [];

		if ($email === '') {
			$errors['email'] = 'L’adresse email est requise.';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Le format de l’email est invalide.';
		}

		if ($password === '') {
			$errors['password'] = 'Le mot de passe est requis.';
		}

		$user = null;
		if (empty($errors)) {
			$user = $this->userModel->getByEmail($email);
			if (!$user) {
				$errors['email'] = 'Aucun compte trouvé avec cette adresse email.';
			} elseif (!$this->userModel->verifyPassword($password, (string) $user['password_hash'])) {
				$errors['password'] = 'Mot de passe incorrect.';
			}
		}

		if (!empty($errors)) {
			return redirect()->back()
				->with('errors', $errors)
				->withInput();
		}

		$this->session->set([
			'user_id' => $user['id'],
			'user_name' => $user['name'],
			'user_email' => $user['email'],
			'is_admin' => (int) ($user['is_admin'] ?? 0),
			'is_logged_in' => true,
			'auth_remember' => $remember,
		]);

		$this->userModel->updateLastLogin($user['id']);

		$redirectUrl = (int) ($user['is_admin'] ?? 0) === 1 ? '/admin' : '/dashboard';

		return redirect()->to($redirectUrl)
			->with('success', 'Connexion réussie. Heureux de vous revoir sur VitalPath.');
	}

	// Étape 1 de l'inscription : données personnelles
	public function register_step1()
	{
		if ($this->request->is('get')) {
			return view('auth/register_step1');
		}

		$full_name = trim($this->request->getPost('full_name'));
		$email = trim($this->request->getPost('email'));
		$gender = trim($this->request->getPost('gender'));
		$password = (string) $this->request->getPost('password');
		$errors = [];

		if ($full_name === '') {
			$errors['full_name'] = 'Le nom complet est requis.';
		} elseif (mb_strlen($full_name) < 3) {
			$errors['full_name'] = 'Le nom doit contenir au moins 3 caractères.';
		}

		if ($email === '') {
			$errors['email'] = 'L’adresse email est requise.';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Le format de l’email est invalide.';
		} elseif ($this->userModel->emailExists($email)) {
			$errors['email'] = 'Cet email est déjà utilisé.';
		}

		if (!in_array($gender, ['male', 'female', 'other'], true)) {
			$errors['gender'] = 'Veuillez choisir un genre valide.';
		}

		if ($password === '') {
			$errors['password'] = 'Le mot de passe est requis.';
		} elseif (mb_strlen($password) < 8) {
			$errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères.';
		}

		if (!empty($errors)) {
			return redirect()->back()
				->with('errors', $errors)
				->withInput();
		}

		$this->session->set([
			'register_step1' => [
				'full_name' => $full_name,
				'email' => $email,
				'gender' => $gender,
				'password' => $password,
			]
		]);

		return redirect()->to('/auth/register/step2')
			->with('success', 'Informations personnelles validées');
	}

	// Étape 2 de l'inscription : métriques et création du compte
	public function register_step2()
	{
		$step1Data = $this->session->get('register_step1');

		if (!$step1Data) {
			return redirect()->to('/auth/register/step1')
				->with('error', 'Veuillez commencer par l’étape 1.');
		}

		if ($this->request->is('get')) {
			return view('auth/register_step2', [
				'step1' => $step1Data,
			]);
		}

		$height_cm = (float) $this->request->getPost('height');
		$weight_kg = (float) $this->request->getPost('weight');
		$age = (int) $this->request->getPost('age');
		$goal = trim($this->request->getPost('goal'));
		$errors = [];

		if ($height_cm < 100 || $height_cm > 250) {
			$errors['height'] = 'La taille doit être comprise entre 100 et 250 cm.';
		}

		if ($weight_kg < 20 || $weight_kg > 300) {
			$errors['weight'] = 'Le poids doit être compris entre 20 et 300 kg.';
		}

		if ($age < 13 || $age > 120) {
			$errors['age'] = 'L’âge doit être compris entre 13 et 120 ans.';
		}

		if (!in_array($goal, ['augmenter_poids', 'reduire_poids', 'imc_ideal'], true)) {
			$errors['goal'] = 'Veuillez choisir un objectif valide.';
		}

		if (!empty($errors)) {
			return redirect()->back()
				->with('errors', $errors)
				->withInput();
		}

		$full_name = $step1Data['full_name'];
		$email = $step1Data['email'];
		$gender = $step1Data['gender'];
		$password = $step1Data['password'];

		$genderMap = [
			'male' => 'M',
			'female' => 'F',
			'other' => 'O',
		];

		$objectiveMap = [
			'augmenter_poids' => 'augmenter_poids',
			'reduire_poids' => 'reduire_poids',
			'imc_ideal' => 'imc_ideal',
		];

		// Utiliser le Helper pour calculer IMC
		$imc = ImmediateCalculationHelper::calculateIMC($weight_kg, $height_cm);
		$imcCategory = ImmediateCalculationHelper::getIMCCategory($imc);

		$userData = [
			'name' => $full_name,
			'email' => $email,
			'gender' => $genderMap[$gender] ?? 'O',
			'height_cm' => $height_cm,
			'weight_kg' => $weight_kg,
			'age' => $age,
			'objective' => $objectiveMap[$goal],
			'imc_value' => $imc,
			'imc_category' => $imcCategory,
			'is_active' => 1,
			'password_hash' => $password,
			'wallet_balance' => 0,
			'is_gold' => 0,
			'is_admin' => 0,
		];

		if (!$this->userModel->insert($userData)) {
			return redirect()->back()
				->with('error', 'Erreur lors de la création du compte.')
				->withInput();
		}

		// Nettoyer la session et rediriger
		$this->session->remove('register_step1');

		// Créer la session utilisateur et rediriger
		if ($newUserId = $this->userModel->getInsertID()) {
			$newUser = $this->userModel->find($newUserId);
			if ($newUser) {
				$this->session->set([
					'user_id' => $newUser['id'],
					'user_name' => $newUser['name'],
					'user_email' => $newUser['email'],
					'is_admin' => (int) ($newUser['is_admin'] ?? 0),
					'is_logged_in' => true,
				]);
				$this->userModel->updateLastLogin($newUser['id']);
			}
		}

		return redirect()->to('/dashboard')
			->with('success', 'Compte créé avec succès! Bienvenue sur VitalPath');
	}

	// Vérifier si l'email existe (AJAX)
	public function emailExists()
	{
		$email = trim((string) $this->request->getPost('email'));

		if ($email === '') {
			$payload = $this->request->getJSON(true);
			if (is_array($payload) && isset($payload['email'])) {
				$email = trim((string) $payload['email']);
			}
		}

		if (!$email) {
			return $this->response->setJSON([
				'exists' => false,
				'csrfHash' => csrf_hash(),
			]);
		}

		$exists = $this->userModel->emailExists($email);

		return $this->response->setJSON([
			'exists' => $exists,
			'csrfHash' => csrf_hash(),
		]);
	}

	public function ajax_check_email()
	{
		return $this->emailExists();
	}

	public function ajax_check_password()
	{
		if ($this->request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
			return $this->response->setStatusCode(403)->setJSON([
				'valid' => false,
				'message' => 'Requête non autorisée',
				'csrfHash' => csrf_hash(),
			]);
		}

		$password = (string) $this->request->getPost('password');

		if ($password === '') {
			$payload = $this->request->getJSON(true);
			if (is_array($payload) && isset($payload['password'])) {
				$password = (string) $payload['password'];
			}
		}

		if (mb_strlen($password) < 8) {
			return $this->response->setJSON([
				'valid' => false,
				'message' => 'Le mot de passe doit contenir au moins 8 caractères',
				'csrfHash' => csrf_hash(),
			]);
		}

		$hasUppercase = (bool) preg_match('/[A-Z]/', $password);
		$hasLowercase = (bool) preg_match('/[a-z]/', $password);
		$hasDigit = (bool) preg_match('/\d/', $password);

		if (!$hasUppercase || !$hasLowercase || !$hasDigit) {
			return $this->response->setJSON([
				'valid' => false,
				'message' => 'Ajoute une majuscule, une minuscule et un chiffre',
				'csrfHash' => csrf_hash(),
			]);
		}

		return $this->response->setJSON([
			'valid' => true,
			'message' => 'Mot de passe sécurisé ✓',
			'csrfHash' => csrf_hash(),
		]);
	}

	public function ajax_check_login_email()
	{
		$email = trim((string) $this->request->getPost('email'));

		if ($email === '') {
			$payload = $this->request->getJSON(true);
			if (is_array($payload) && isset($payload['email'])) {
				$email = trim((string) $payload['email']);
			}
		}

		if ($email === '') {
			return $this->response->setJSON([
				'exists' => false,
				'message' => 'Saisissez une adresse email.',
				'csrfHash' => csrf_hash(),
			]);
		}

		$exists = $this->userModel->emailExists($email);

		return $this->response->setJSON([
			'exists' => $exists,
			'message' => $exists ? 'Compte trouvé.' : 'Aucun compte ne correspond à cette adresse.',
			'csrfHash' => csrf_hash(),
		]);
	}

	public function ajax_check_login_password()
	{
		if ($this->request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
			return $this->response->setStatusCode(403)->setJSON([
				'valid' => false,
				'message' => 'Requête non autorisée',
				'csrfHash' => csrf_hash(),
			]);
		}

		$email = trim((string) $this->request->getPost('email'));
		$password = (string) $this->request->getPost('password');

		if ($email === '' || $password === '') {
			$payload = $this->request->getJSON(true);
			if (is_array($payload)) {
				$email = $email !== '' ? $email : trim((string) ($payload['email'] ?? ''));
				$password = $password !== '' ? $password : (string) ($payload['password'] ?? '');
			}
		}

		if ($email === '' || $password === '') {
			return $this->response->setJSON([
				'valid' => false,
				'message' => 'Veuillez saisir l’email et le mot de passe.',
				'csrfHash' => csrf_hash(),
			]);
		}

		$user = $this->userModel->getByEmail($email);

		if (!$user) {
			return $this->response->setJSON([
				'valid' => false,
				'message' => 'Aucun compte trouvé pour cette adresse email.',
				'csrfHash' => csrf_hash(),
			]);
		}

		$valid = $this->userModel->verifyPassword($password, (string) $user['password_hash']);

		return $this->response->setJSON([
			'valid' => $valid,
			'message' => $valid ? 'Mot de passe correct.' : 'Mot de passe incorrect.',
			'csrfHash' => csrf_hash(),
		]);
	}


	public function logout()
	{
		$this->session->destroy();
		return redirect()->to('auth/login')
			->with('success', 'Déconnexion réussie.');
	}
}