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
    $result = $db->query("SELECT * FROM Presets WHERE User_ID = $user_id OR User_ID IS NULL");
    
    $presets = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $presets[$row['Type']] = [
            'nom' => $row['Nom'],
            'prenom' => $row['Prenom'],
            'naissance' => $row['Naissance'],
            'niss' => $row['NISS'],
            'numPatient' => $row['NumPatient'],
            'sexe' => $row['Sexe'],
            'typeInterv' => $row['TypeInterv']
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
