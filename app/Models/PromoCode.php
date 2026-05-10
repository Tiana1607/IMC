<?php

namespace App\Models;

use CodeIgniter\Model;

class PromoCode extends Model
{
    protected $table = 'promo_codes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'code',
        'amount',
        'is_used',
        'used_by_user_id',
        'used_at',
        'description',
        'expires_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function findByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }

    public function isCodeValid(string $code): bool
    {
        $promoCode = $this->findByCode($code);

        if (! $promoCode) {
            return false;
        }

        if ((int) $promoCode['is_used'] === 1) {
            return false;
        }

        if ($promoCode['expires_at'] !== null) {
            $expiresAt = strtotime($promoCode['expires_at']);
            if ($expiresAt < time()) {
                return false;
            }
        }

        return true;
    }

    public function markAsUsed(int $promoCodeId, int $userId): bool
    {
        return (bool) $this->update($promoCodeId, [
            'is_used' => 1,
            'used_by_user_id' => $userId,
            'used_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function getAvailableCodes(): array
    {
        return $this->where('is_used', 0)
            ->where(function ($builder) {
                $builder->where('expires_at', null);
                $builder->orWhere('expires_at >=', date('Y-m-d H:i:s'));
            })
            ->orderBy('amount', 'DESC')
            ->findAll();
    }

    public function getUsedCodes(): array
    {
        return $this->where('is_used', 1)
            ->orderBy('used_at', 'DESC')
            ->findAll();
    }
}
