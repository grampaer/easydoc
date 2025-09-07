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

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $stmt = $db->prepare('INSERT INTO Interventions (
        user_id, nom, prenom, naissance, niss, numPatient, sexe, 
        dateOp, anesthesie, assistants, typeInterv, codesInami, cote, membre
    ) VALUES (
        :user_id, :nom, :prenom, :naissance, :niss, :numPatient, :sexe, 
        :dateOp, :anesthesie, :assistants, :typeInterv, :codesInami, :cote, :membre
    )');

    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':nom' => $data['nom'],
        ':prenom' => $data['prenom'],
        ':naissance' => $data['naissance'],
        ':niss' => $data['niss'],
        ':numPatient' => $data['numPatient'],
        ':sexe' => $data['sexe'],
        ':dateOp' => $data['dateOp'],
        ':anesthesie' => $data['anesthesie'],
        ':assistants' => $data['assistants'],
        ':typeInterv' => $data['typeInterv'],
        ':codesInami' => $data['codesInami'],
        ':cote' => $data['cote'],
        ':membre' => $data['membre']
    ]);

    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
