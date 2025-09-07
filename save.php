<?php

if (!session_id()) {
    session_start();
}

include_once("db.php");

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        throw new RuntimeException('Accès non autorisé - utilisateur non connecté');
    }

    $response = [];

    if (isset($_GET['template_id'])) {
        $template_id = (int)$_GET['template_id'];
        $folder_id = $db->insertFolder($template_id);
        $fields = $db->getFieldsId($template_id);
        
        $response = [
            'status' => 'success',
            'data' => [
                'folder_id' => $folder_id,
                'fields' => $fields
            ]
        ];
    } 
    elseif (isset($_GET['field_id'], $_GET['field_value'])) {
        $field_id = (int)$_GET['field_id'];
        $field_value = $_GET['field_value'];
        $result = $db->saveField($_SESSION['user_id'], $field_id, $field_value);
        
        $response = [
            'status' => 'success',
            'data' => $result
        ];
    } 
    else {
        throw new RuntimeException('Paramètres de requête invalides');
    }

    echo json_encode($response);

} catch (RuntimeException $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Erreur interne du serveur'
    ]);
}



