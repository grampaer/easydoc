<?php
session_start();
header('Content-Type: application/json');
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $token = trim($input['token'] ?? '');
    $newPassword = trim($input['password'] ?? '');
    
    if (empty($token) || empty($newPassword)) {
        echo json_encode(['success' => false, 'message' => 'Token et nouveau mot de passe requis']);
        exit;
    }
    
    if (strlen($newPassword) < 5) {
        echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 5 caractères']);
        exit;
    }
    
    $db = new MyDB();
    
    // Vérifier le token
    $stmt = $db->pdo->prepare("SELECT Email FROM PasswordReset WHERE Token = :token AND Expiry > NOW()");
    $stmt->execute([':token' => $token]);
    $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($resetRequest) {
        $email = $resetRequest['Email'];
        
        // Mettre à jour le mot de passe
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $db->pdo->prepare("UPDATE Users SET Password = :password WHERE Email = :email");
        $result = $stmt->execute([
            ':password' => $hashedPassword,
            ':email' => $email
        ]);
        
        if ($result) {
            // Supprimer le token utilisé
            $stmt = $db->pdo->prepare("DELETE FROM PasswordReset WHERE Token = :token");
            $stmt->execute([':token' => $token]);
            
            echo json_encode(['success' => true, 'message' => 'Mot de passe réinitialisé avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la réinitialisation']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Token invalide ou expiré']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>
