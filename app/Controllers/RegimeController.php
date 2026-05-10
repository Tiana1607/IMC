<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Regime;
use App\Models\UserRegime;
use App\Models\Wallet;

class RegimeController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    /**
     * Display available regimes for purchase
     * GET /regimes
     */
    public function catalog()
    {
        $userId = (int) ($this->session->get('user_id') ?? 0);
        if ($userId === 0) {
            return redirect()->to('/auth/login');
        }

        $regimeModel = new Regime();
        $userRegimeModel = new UserRegime();

        $regimes = $regimeModel->where('is_active', 1)->findAll();
        $purchases = $userRegimeModel->getUserPurchases($userId);

        $purchasedRegimeIds = [];
        foreach ($purchases as $purchase) {
            $purchasedRegimeIds[] = (int) ($purchase['regime_id'] ?? 0);
        }

        $db = db_connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();

        return view('regimes/catalog', [
            'regimes' => $regimes,
            'purchasedRegimeIds' => $purchasedRegimeIds,
            'isGold' => (int) ($user['is_gold'] ?? 0) === 1,
            'walletBalance' => (new Wallet())->getBalance($userId),
        ]);
    }

    /**
     * Purchase a regime
     * POST /regime/:id/purchase
     */
    public function purchase(int $regimeId)
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $userId = (int) ($this->session->get('user_id') ?? 0);
        if ($userId === 0) {
            return redirect()->to('/auth/login');
        }

        $regimeModel = new Regime();
        $userRegimeModel = new UserRegime();
        $walletModel = new Wallet();

        $regime = $regimeModel->find($regimeId);
        if (!$regime) {
            $this->session->setFlashdata('error', 'Régime non trouvé.');
            return redirect()->back();
        }

        $db = db_connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            $this->session->setFlashdata('error', 'Utilisateur non trouvé.');
            return redirect()->back();
        }

        $basePrice = (float) ($regime['price_per_week'] ?? 0) * (int) ($regime['duration_weeks'] ?? 1);
        $discount = $user['is_gold'] == 1 ? 0.15 : 0;
        $finalPrice = $basePrice * (1 - $discount);

        $balance = $walletModel->getBalance($userId);
        if ($balance < $finalPrice) {
            $this->session->setFlashdata('error', 'Solde insuffisant. Vous avez besoin de €' . number_format($finalPrice - $balance, 2) . ' de plus.');
            return redirect()->back();
        }

        $ok = $walletModel->subtractBalance($userId, $finalPrice);
        if (!$ok) {
            $this->session->setFlashdata('error', 'Impossible de débiter le portefeuille.');
            return redirect()->back();
        }

        $startsAt = date('Y-m-d H:i:s');
        $endsAt = date('Y-m-d H:i:s', strtotime('+' . (int) ($regime['duration_weeks'] ?? 1) . ' weeks'));

        $userRegimeModel->purchaseRegime(
            $userId,
            $regimeId,
            $finalPrice,
            $startsAt,
            $endsAt
        );

        $discountText = $discount > 0 ? ' (remise Gold -' . (int) ($discount * 100) . '%)' : '';
        $this->session->setFlashdata('success', 'Achat confirmé : ' . esc($regime['name']) . ' pour €' . number_format($finalPrice, 2) . $discountText);

        return redirect()->back();
    }
}
