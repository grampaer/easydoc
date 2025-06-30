<?php
// Connexion à SQLite (ou crée le fichier s'il n'existe pas)
$db = new PDO('sqlite:'./easydb.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json');

// Récupération et validation
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (strlen($username) < 3 || strlen($password) < 5) {
  echo json_encode(['success' => false, 'message' => 'Nom d’utilisateur ou mot de passe trop court']);
  exit;
}

// Vérification des doublons
$stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE UserName = :username OR Email = :email");
$stmt->execute([':username' => $username, ':email' => $email]);
if ($stmt->fetchColumn() > 0) {
  echo json_encode(['success' => false, 'message' => 'Utilisateur ou email déjà enregistré']);
  exit;
}

// Hachage du mot de passe
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insertion en base
$stmt = $db->prepare("INSERT INTO users (UserName, Email, Password) VALUES (:username, :email, :password)");
$stmt->execute([
  ':username' => $username,
  ':email' => $email,
  ':password' => $hashedPassword
]);

echo json_encode(['success' => true]);
