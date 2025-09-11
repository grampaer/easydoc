<?php
session_start();
header('Content-Type: application/json');

include_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $nom = trim($input['nom'] ?? '');
    $prenom = trim($input['prenom'] ?? '');
    $hopital = trim($input['hopital'] ?? '');
    $specialite = trim($input['specialite'] ?? '');
    $adresse = trim($input['adresse'] ?? '');
    
    $db = new MyDB();
    if ($db->updateUserInfo($_SESSION['user_id'], $nom, $prenom, $specialite, $hopital, $adresse)) {
        echo json_encode(['success' => true, 'message' => 'Informations sauvegardées']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la sauvegarde']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>
