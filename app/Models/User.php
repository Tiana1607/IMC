<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'email',
        'password_hash',
        'name',
        'gender',
        'height_cm',
        'weight_kg',
        'age',
        'objective',
        'imc_value',
        'imc_category',
        'wallet_balance',
        'is_gold',
        'is_admin',
        'is_active',
        'last_login'
    ];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    // Hashage mdp
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password_hash'])) {
            if ($data['data']['password_hash'] !== null) {
                $data['data']['password_hash'] = password_hash(
                    $data['data']['password_hash'],
                    PASSWORD_DEFAULT
                );
            }
        }
        return $data;
    }

    /**
     * Calculer IMC (Indice de Masse Corporelle)
     * Formule : IMC = poids (kg) / (taille (m))²
     */
    public function calculateIMC($weight_kg, $height_cm)
    {
        if ($height_cm <= 0 || $weight_kg <= 0) {
            return null;
        }
        $height_m = $height_cm / 100;
        $imc = $weight_kg / ($height_m * $height_m);
        return round($imc, 2);
    }

    // Catégorie IMC
    public function getIMCCategory($imc)
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

    // Vérif email
    public function emailExists($email)
    {
        return $this->where('email', $email)->first() !== null;
    }

    // Email utilisateur
    public function getByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    // Vérif mdp
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    // Màj portefeuille
    // public function updateWallet($userId, $amount, $operation = 'add')
    // {
    //     $user = $this->find($userId);
    //     if (!$user) {
    //         return false;
    //     }

    //     $newBalance = $operation === 'add'
    //         ? $user['wallet_balance'] + $amount
    //         : $user['wallet_balance'] - $amount;

    //     if ($newBalance < 0) {
    //         return false;
    //     }

    //     return $this->update($userId, ['wallet_balance' => $newBalance]);
    // }

    // Màj dernier login
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

}
