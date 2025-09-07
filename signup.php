<?php
session_start();
header('Content-Type: application/json');

// Inclure le fichier de base de données
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    $nom = trim($input['nom'] ?? '');
    $prenom = trim($input['prenom'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';
    $specialite = trim($input['specialite'] ?? '');
    
    // Valider les données
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($specialite)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont obligatoires']);
        exit;
    }
    
    if (strlen($password) < 5) {
        echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 5 caractères']);
        exit;
    }
    
    // Créer un nom d'utilisateur à partir du nom et prénom
    $username = strtolower($prenom . '.' . $nom);
    
    // Vérifier si l'utilisateur existe déjà en utilisant la fonction de la classe MyDB
    $db = new MyDB();
    if ($db->isUserExist($username, $email)) {
        echo json_encode(['success' => false, 'message' => 'Nom d\'utilisateur ou email déjà existant']);
        exit;
    }
    
    // Insérer l'utilisateur en utilisant la fonction de la classe MyDB
    if ($db->insertUser($username, $email, $password, $nom, $prenom, $specialite)) {
        echo json_encode(['success' => true, 'message' => 'Utilisateur créé avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de l\'utilisateur']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>
