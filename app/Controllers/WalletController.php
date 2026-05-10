<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Wallet;
use App\Models\PromoCode;

class WalletController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index()
    {
        $userId = (int) ($this->session->get('user_id') ?? 0);
        if ($userId === 0) {
            return redirect()->to('/auth/login');
        }

        $walletModel = new Wallet();
        $promoModel = new PromoCode();

        $balance = $walletModel->getBalance($userId);

        $usedCodes = $promoModel->where('used_by_user_id', $userId)
            ->orderBy('used_at', 'DESC')
            ->findAll();

        $db = db_connect();
        $purchases = [];
        if ($db->tableExists('user_regimes')) {
            $purchases = $db->table('user_regimes ur')
                ->select('ur.*, r.name AS regime_name')
                ->join('regimes r', 'r.id = ur.regime_id', 'left')
                ->where('ur.user_id', $userId)
                ->orderBy('ur.purchased_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        $transactions = [];
        foreach ($usedCodes as $uc) {
            $transactions[] = [
                'type' => 'credit',
                'description' => 'Crédit promo (' . ($uc['code'] ?? '') . ')',
                'date' => $uc['used_at'] ?? $uc['updated_at'] ?? null,
                'status' => 'completed',
                'amount' => (float) ($uc['amount'] ?? 0),
            ];
        }

        foreach ($purchases as $p) {
            $transactions[] = [
                'type' => 'debit',
                'description' => ($p['regime_name'] ?? 'Achat régime'),
                'date' => $p['purchased_at'] ?? $p['created_at'] ?? null,
                'status' => $p['is_active'] == 1 ? 'active' : 'completed',
                'amount' => -1 * ((float) ($p['price_paid'] ?? 0)),
            ];
        }

        usort($transactions, function ($a, $b) {
            $ta = strtotime($a['date'] ?? '1970-01-01');
            $tb = strtotime($b['date'] ?? '1970-01-01');
            return $tb <=> $ta;
        });

        $currentPlan = 'Standard';
        if (! empty($purchases)) {
            $latestPurchase = $purchases[0];
            if (! empty($latestPurchase['regime_name'])) {
                $currentPlan = $latestPurchase['regime_name'];
            }
        }

        $nowMonth = date('Y-m');
        $monthlySpent = 0.0;
        $monthlySavings = 0.0;
        $activeRewards = 0;

        foreach ($transactions as $transaction) {
            $transactionMonth = ! empty($transaction['date']) ? date('Y-m', strtotime($transaction['date'])) : null;
            if ($transactionMonth !== $nowMonth) {
                continue;
            }

            if (($transaction['type'] ?? '') === 'debit') {
                $monthlySpent += abs((float) ($transaction['amount'] ?? 0));
            }

            if (($transaction['type'] ?? '') === 'credit') {
                $monthlySavings += abs((float) ($transaction['amount'] ?? 0));
                $activeRewards++;
            }
        }

        $offer = null;
        $offerDb = $db->table('offers')->where('is_active', 1)->limit(1)->get()->getRowArray();
        if ($offerDb) {
            $offer = [
                'name' => $offerDb['name'] ?? '',
                'label' => $offerDb['label'] ?? 'Offre limitée',
                'discount_percent' => (int) ($offerDb['discount_percent'] ?? 15),
                'description' => $offerDb['description'] ?? '',
                'cta_text' => $offerDb['cta_text'] ?? 'Être un membre Gold',
            ];
        }

        $promoCardTitle = 'Utiliser un code promo';
        $promoCardDescription = 'Entrez votre code ci-dessous pour ajouter des crédits instantanément à votre compte.';

        return view('wallet', [
            'balance' => $balance,
            'transactions' => $transactions,
            'usedCodes' => $usedCodes,
            'currentPlan' => $currentPlan,
            'monthlySpent' => $monthlySpent,
            'monthlySavings' => $monthlySavings,
            'activeRewards' => $activeRewards,
            'totalTransactions' => count($transactions),
            'offer' => $offer,
            'promoCardTitle' => $promoCardTitle,
            'promoCardDescription' => $promoCardDescription,
        ]);
    }

    public function addCode()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }

        $userId = (int) ($this->session->get('user_id') ?? 0);
        if ($userId === 0) {
            return redirect()->to('/auth/login');
        }

        $code = trim((string) $this->request->getPost('promo_code'));

        if ($code === '') {
            $this->session->setFlashdata('error', 'Veuillez entrer un code promo.');
            return redirect()->back()->withInput();
        }

        $promoModel = new PromoCode();
        $walletModel = new Wallet();

        $promo = $promoModel->findByCode($code);

        if (! $promo || ! $promoModel->isCodeValid($code)) {
            $this->session->setFlashdata('error', 'Code invalide ou expiré.');
            return redirect()->back()->withInput();
        }

        $amount = (float) ($promo['amount'] ?? 0);

        $ok = $walletModel->addBalance($userId, $amount);
        if (! $ok) {
            $this->session->setFlashdata('error', 'Impossible de créditer le portefeuille.');
            return redirect()->back();
        }

        $promoModel->markAsUsed((int) $promo['id'], $userId);

        $this->session->setFlashdata('success', 'Code appliqué — ' . number_format($amount, 2) . ' ajouté au portefeuille.');

        return redirect()->back();
    }
}
