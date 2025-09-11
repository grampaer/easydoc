<?php
session_start();
require_once 'db.php';

$db = new MyDB();

$templateType = $_GET['type'] ?? '';

if (empty($templateType)) {
    echo json_encode(['success' => false, 'error' => 'Type de template non spécifié']);
    exit;
}

$template = $db->getInterventionTemplate($templateType);

if ($template) {
    echo json_encode(['success' => true, 'template' => $template['Data']]);
} else {
    echo json_encode(['success' => true, 'template' => null]);
}
?>
