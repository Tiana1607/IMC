<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRegime extends Model
{
    protected $table = 'user_regimes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'regime_id', 'price_paid', 'purchased_at', 'starts_at', 'ends_at', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


    public function purchaseRegime(int $userId, int $regimeId, float $pricePaid, ?string $startsAt = null, ?string $endsAt = null)
    {
        $data = [
            'user_id' => $userId,
            'regime_id' => $regimeId,
            'price_paid' => $pricePaid,
            'purchased_at' => date('Y-m-d H:i:s'),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'is_active' => 1,
        ];

        return $this->insert($data);
    }

    public function getUserPurchases(int $userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('purchased_at', 'DESC')
            ->findAll();
    }

    public function hasPurchased(int $userId, int $regimeId)
    {
        $result = $this->where('user_id', $userId)
            ->where('regime_id', $regimeId)
            ->first();

        return $result !== null;
    }

    public function getActivePurchases(int $userId)
    {
        return $this->where('user_id', $userId)
            ->where('is_active', 1)
            ->orderBy('purchased_at', 'DESC')
            ->findAll();
    }
}
