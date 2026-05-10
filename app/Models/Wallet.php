<?php

namespace App\Models;

use CodeIgniter\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id',
        'balance',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getBalance(int $userId): float
    {
        $wallet = $this->where('user_id', $userId)->first();

        if (! $wallet) {
            return 0.0;
        }

        return (float) ($wallet['balance'] ?? 0);
    }

    public function addBalance(int $userId, float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        $db = $this->db;
        $db->transBegin();

        $wallet = $this->where('user_id', $userId)->first();

        if (! $wallet) {
            $this->insert([
                'user_id' => $userId,
                'balance' => $amount,
            ]);
        } else {
            $newBalance = (float) $wallet['balance'] + $amount;
            $this->update((int) $wallet['id'], ['balance' => $newBalance]);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return false;
        }

        $db->transCommit();
        return true;
    }

    public function subtractBalance(int $userId, float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        $db = $this->db;
        $db->transBegin();

        $wallet = $this->where('user_id', $userId)->first();
        $currentBalance = (float) ($wallet['balance'] ?? 0);

        if ($currentBalance < $amount) {
            $db->transRollback();
            return false;
        }

        $newBalance = $currentBalance - $amount;
        $this->update((int) $wallet['id'], ['balance' => $newBalance]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return false;
        }

        $db->transCommit();
        return true;
    }
}
