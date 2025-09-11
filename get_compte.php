<?php
session_start();
header('Content-Type: application/json');

include_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

$db = new MyDB();
$userInfo = $db->getUserInfo($_SESSION['user_id']);

if ($userInfo) {
    echo json_encode([
        'success' => true,
        'data' => [
            'nom' => $userInfo['LastName'],
            'prenom' => $userInfo['FirstName'],
            'specialite' => $userInfo['INAME'],
            'hopital' => $userInfo['Hopital'],
            'adresse' => $userInfo['Adresse']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
}
?>
