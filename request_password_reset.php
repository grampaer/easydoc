<?php
session_start();
header('Content-Type: application/json');
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = trim($input['email'] ?? '');
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email requis']);
        exit;
    }
    
    $db = new MyDB();
    
    // Vérifier si l'email existe
    $stmt = $db->pdo->prepare("SELECT ID FROM Users WHERE Email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Générer un token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Supprimer les anciens tokens
        $stmt = $db->pdo->prepare("DELETE FROM PasswordReset WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        
        // Insérer le nouveau token
        $stmt = $db->pdo->prepare("INSERT INTO PasswordReset (Email, Token, Expiry) VALUES (:email, :token, :expiry)");
        $stmt->execute([
            ':email' => $email,
            ':token' => $token,
            ':expiry' => $expiry
        ]);
        
        // Envoyer l'email (simulation)
        // En production, vous enverriez un vrai email avec un lien contenant le token
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $token;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Email de réinitialisation envoyé (simulation)',
            'reset_link' => $resetLink // À retirer en production
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email non trouvé']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>
