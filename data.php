<?php

if (!session_id()) {
    session_start();
}

include_once("db.php");

header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Constantes pour les noms de mois
define('MONTH_NAMES', [
    "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
    "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
]);

try {
    if (!isset($_SESSION['user_id'])) {
        throw new RuntimeException('Accès refusé: utilisateur non connecté', 403);
    }

    // Validation de l'ID utilisateur
    $user_id = (int)$_SESSION['user_id'];
    if ($user_id <= 0) {
        throw new RuntimeException('ID utilisateur invalide', 400);
    }

    $rows = $db->getInformationHisByMonth();
    
    if (!is_array($rows)) {
        throw new RuntimeException('Erreur lors de la récupération des données', 500);
    }

    // Initialisation des compteurs pour chaque mois
    $counts = array_fill(0, 12, 0);

    foreach ($rows as $row) {
        if (!isset($row['month']) || !isset($row['count'])) {
            continue; // Ignore les lignes malformées
        }
        
        $monthIndex = (int)$row["month"] - 1;
        if ($monthIndex < 0 || $monthIndex > 11) {
            continue;
        }
        $counts[$monthIndex] = (int)$row["count"];
    }

    $response = [
        'status' => 'success',
        'data' => [
            "labels" => MONTH_NAMES,
            "counts" => $counts,
            "total" => array_sum($counts),
            "max" => max($counts) ?: 0,
            "min" => min($counts) ?: 0
        ],
        'timestamp' => date('c'),
        'version' => '1.0'
    ];

    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (RuntimeException $e) {
    $code = is_int($e->getCode()) && $e->getCode() > 0 ? $e->getCode() : 400;
    http_response_code($code);
    echo json_encode([
        'status' => 'error',
        'error' => [
            'code' => $code,
            'message' => $e->getMessage(),
            'details' => $code === 500 ? null : 'Vérifiez vos paramètres'
        ]
    ], JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    error_log('Data Error: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'error' => [
            'code' => 500,
            'message' => 'Erreur interne du serveur',
            'details' => 'Notre équipe a été notifiée'
        ]
    ], JSON_PRETTY_PRINT);
}
