<?php
if (!session_id()) {
    session_start();
}

include_once("db.php");

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new RuntimeException('Utilisateur non connecté');
    }

    // Récupérer l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];
    
    // Utiliser la méthode getInterventions de la classe MyDB
    $interventions = $db->getInterventions($user_id);
    
    echo json_encode([
        'success' => true,
        'data' => $interventions
    ]);
    
} catch (RuntimeException $e) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur interne du serveur: ' . $e->getMessage()
    ]);
}
?>
