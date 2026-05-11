<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PdfController extends BaseController
{
    public function exportRecommendations()
    {
        $session = session();
        $userId = (int) ($session->get('user_id') ?? 0);
        if ($userId === 0) {
            return redirect()->to('/auth/login');
        }

        $db = db_connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            $session->setFlashdata('error', 'Utilisateur non trouvé.');
            return redirect()->back();
        }

        $diets = [];
        $activities = [];

        if ($db->tableExists('regimes')) {
            $diets = $db->table('regimes')
                ->limit(3)
                ->orderBy('calorie_target', 'DESC')
                ->get()
                ->getResultArray();
        }

        if ($db->tableExists('activities')) {
            $activities = $db->table('activities')
                ->limit(2)
                ->orderBy('calories_per_hour', 'DESC')
                ->get()
                ->getResultArray();
        }

        $html = $this->buildPdfContent($user, $diets, $activities);

        require_once ROOTPATH . 'vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);

        $mpdf->WriteHTML($html);

        // Output PDF as download
        $filename = 'recommendations_' . $user['name'] . '_' . date('Y-m-d') . '.pdf';
        $mpdf->Output($filename, 'D'); // D = download
    }

    private function buildPdfContent(array $user, array $diets, array $activities): string
    {
        $objective = $user['objective'] ?? 'non défini';
        $todayFormatted = date('d/m/Y');
        $objectiveLabels = [
            'augmenter_poids' => 'Prise de poids',
            'reduire_poids' => 'Perte de poids',
            'imc_ideal' => 'IMC idéal',
        ];
        $objectiveLabel = $objectiveLabels[$objective] ?? ucwords(str_replace('_', ' ', $objective));

        $html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Plan de Recommandations</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        h1 { color: #006e2f; border-bottom: 3px solid #006e2f; padding-bottom: 10px; }
        h2 { color: #006e2f; margin-top: 20px; margin-bottom: 10px; }
        .header { margin-bottom: 30px; }
        .user-info { background: #f5f5f5; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .regime-card, .activity-card { page-break-inside: avoid; margin-bottom: 20px; border: 1px solid #ddd; padding: 12px; border-radius: 5px; }
        .regime-card h3, .activity-card h3 { color: #006e2f; margin: 0 0 8px 0; }
        .badge { display: inline-block; background: #006e2f; color: white; padding: 4px 8px; border-radius: 3px; font-size: 12px; margin-bottom: 8px; }
        .composition { font-size: 12px; color: #666; margin: 8px 0; }
        .price { font-weight: bold; color: #006e2f; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 2px solid #006e2f; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Plan de Recommandations Personnalisé</h1>
        <div class="user-info">
            <strong>Utilisateur :</strong> {$user['name']}<br>
            <strong>Objectif :</strong> {$objectiveLabel}<br>
            <strong>IMC Actuel :</strong> {$user['imc_value']} ({$user['imc_category']})<br>
            <strong>Poids :</strong> {$user['weight_kg']} kg | <strong>Taille :</strong> {$user['height_cm']} cm<br>
            <strong>Date :</strong> {$todayFormatted}
        </div>
    </div>

    <h2>Régimes Recommandés (Top 3)</h2>
HTML;

        if (!empty($diets)) {
            foreach ($diets as $regime) {
                $price = (float) ($regime['price_per_week'] ?? 0);
                $isGold = (int) ($user['is_gold'] ?? 0) === 1;
                $finalPrice = $isGold ? $price * 0.85 : $price;
                $priceFormatted = number_format($finalPrice, 2, ',', ' ');
                $originalPriceFormatted = number_format($price, 2, ',', ' ');

                $meatPct = (int) ($regime['meat_percent'] ?? 0);
                $fishPct = (int) ($regime['fish_percent'] ?? 0);
                $poultrySct = (int) ($regime['poultry_percent'] ?? 0);

                $html .= <<<HTML
    <div class="regime-card">
        <h3>{$regime['name']}</h3>
        <span class="badge">{$regime['duration_weeks']} semaines</span>
        <p>{$regime['description']}</p>
        <div class="composition">
            <strong>Composition :</strong> Viande {$meatPct}% • Poisson {$fishPct}% • Volaille {$poultrySct}%
        </div>
        <p><strong>Cibles caloriques :</strong> {$regime['calorie_target']} kcal/jour</p>
        <p class="price">Prix : {$priceFormatted} €/semaine
HTML;
                if ($isGold) {
                    $html .= " <small style='text-decoration:line-through; color:#999;'>{$originalPriceFormatted} €</small> <strong style='color:#b45309;'>-15% GOLD</strong>";
                }
                $html .= "</p>\n    </div>\n";
            }
        } else {
            $html .= "    <p><em>Aucun régime disponible.</em></p>\n";
        }

        $html .= "    <h2>Activités Suggérées (Top 2)</h2>\n";

        if (!empty($activities)) {
            $html .= <<<HTML
    <table>
        <thead>
            <tr>
                <th>Activité</th>
                <th>Durée</th>
                <th>Calories/heure</th>
                <th>Intensité</th>
            </tr>
        </thead>
        <tbody>
HTML;
            foreach ($activities as $activity) {
                $intensity = $activity['intensity'] ?? 'normal';
                $intensityLabels = [
                    'low' => 'Faible',
                    'medium' => 'Moyen',
                    'high' => 'Élevée',
                ];
                $intensityLabel = $intensityLabels[$intensity] ?? ucfirst($intensity);
                $durationMins = (int) ($activity['duration_minutes'] ?? 30);

                $html .= <<<HTML
            <tr>
                <td>{$activity['name']}</td>
                <td>{$durationMins} min</td>
                <td>{$activity['calories_per_hour']} kcal</td>
                <td>{$intensityLabel}</td>
            </tr>
HTML;
            }
            $html .= "        </tbody>\n    </table>\n";
        } else {
            $html .= "    <p><em>Aucune activité disponible.</em></p>\n";
        }

        $html .= <<<HTML
    <div class="footer">
        <p>Ce plan a été généré automatiquement basé sur votre profil VitalPath.</p>
        <p>Consultez un professionnel de la santé avant de débuter un nouveau régime ou programme d'activité.</p>
    </div>
</body>
</html>
HTML;

        return $html;
    }
}
