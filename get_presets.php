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

    $user_id = $_SESSION['user_id'];
    
    // Récupérer les presets depuis la base de données
    // Adaptez cette requête à votre structure de base de données
    $result = $db->query("SELECT * FROM presets WHERE user_id = $user_id");
    
    $presets = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $presets[$row['type']] = [
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'naissance' => $row['naissance'],
            'niss' => $row['niss'],
            'numPatient' => $row['num_patient'],
            'sexe' => $row['sexe'],
            'typeInterv' => $row['type_interv']
        ];
    }
    
    // Ajouter le preset vierge
    $presets['vierge'] = [
        'nom' => "",
        'prenom' => "",
        'naissance' => "",
        'niss' => "",
        'numPatient' => "",
        'sexe' => "Sexe",
        'typeInterv' => ""
    ];
    
    echo json_encode([
        'success' => true,
        'data' => $presets
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
