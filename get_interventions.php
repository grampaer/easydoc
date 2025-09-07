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

    // Implémentez ici la logique pour récupérer les interventions depuis la base de données
    // Ceci est un exemple, adaptez-le à votre structure de base de données
    $user_id = $_SESSION['user_id'];
    $result = $db->query("SELECT * FROM Interventions WHERE User_ID = $user_id");
    
    $interventions = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $interventions[] = $row;
    }
    
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
        'error' => 'Erreur interne du serveur'
    ]);
}
?>
