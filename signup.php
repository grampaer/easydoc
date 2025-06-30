<?php

include_once("db.php");

header('Content-Type: application/json');

// Récupération et validation

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (strlen($username) < 3 || strlen($password) < 5) {
    echo json_encode(['success' => false, 'message' => 'Nom d’utilisateur ou mot de passe trop court']);
    exit;
}

if ($db->isUserExist($username, $email)) {   
    echo json_encode(['success' => false, 'message' => 'Utilisateur ou email déjà enregistré']);
    exit;
}    

if ($db->insertUser($username, $email, $password)) {
    echo json_encode(['success' => true]);
}
