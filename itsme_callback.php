<?php
session_start();
require_once 'db.php';

// Vérifier si une erreur est retournée
if (isset($_GET['error'])) {
    header('Location: index.html?error=itsme_auth_failed');
    exit;
}

// Vérifier le code d'autorisation
if (!isset($_GET['code'])) {
    header('Location: index.html?error=no_authorization_code');
    exit;
}

$authorizationCode = $_GET['code'];
$state = $_GET['state'] ?? '';

// Vérifier le state pour la protection CSRF
$storedState = $_SESSION['itsme_oauth_state'] ?? '';
if ($state !== $storedState) {
    header('Location: index.html?error=invalid_state');
    exit;
}

// Configuration itsme
$clientId = 'VOTRE_CLIENT_ID';
$clientSecret = 'VOTRE_CLIENT_SECRET';
$redirectUri = 'https://votre-domaine.com/itsme_callback.php';

// Échanger le code d'autorisation contre un token d'accès
$tokenUrl = 'https://connect.itsme.be/oidc/token';
$postData = [
    'grant_type' => 'authorization_code',
    'code' => $authorizationCode,
    'redirect_uri' => $redirectUri,
    'client_id' => $clientId,
    'client_secret' => $clientSecret
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

$response = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($response, true);

if (!isset($tokenData['access_token'])) {
    header('Location: index.html?error=token_acquisition_failed');
    exit;
}

// Récupérer les informations utilisateur
$userInfoUrl = 'https://connect.itsme.be/oidc/userinfo';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $tokenData['access_token'],
    'Content-Type: application/json'
]);

$userInfoResponse = curl_exec($ch);
curl_close($ch);

$userInfo = json_decode($userInfoResponse, true);

if (isset($userInfo['error'])) {
    header('Location: index.html?error=user_info_failed');
    exit;
}

// Vérifier si l'utilisateur existe dans notre base de données
$db = new MyDB();
$email = $userInfo['email'] ?? '';

// Chercher l'utilisateur par email
$stmt = $db->pdo->prepare("SELECT * FROM Users WHERE Email = :email");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Connecter l'utilisateur existant
    $_SESSION['user_id'] = $user['ID'];
    $_SESSION['username'] = $user['UserName'];
    $_SESSION['logged'] = true;
    
    header('Location: index.html');
    exit;
} else {
    // Créer un nouvel utilisateur
    $username = $userInfo['sub'] ?? $email; // Utiliser l'ID itsme ou l'email comme nom d'utilisateur
    $firstName = $userInfo['given_name'] ?? '';
    $lastName = $userInfo['family_name'] ?? '';
    
    // Insérer le nouvel utilisateur
    $stmt = $db->pdo->prepare("INSERT INTO Users (UserName, Email, Password, FirstName, LastName, INAME) 
                              VALUES (:username, :email, :password, :firstName, :lastName, :specialite)");
    $result = $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), // Mot de passe aléatoire
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':specialite' => 'Médecine générale'
    ]);
    
    if ($result) {
        $userId = $db->pdo->lastInsertId();
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['logged'] = true;
        
        header('Location: index.html');
        exit;
    } else {
        header('Location: index.html?error=user_creation_failed');
        exit;
    }
}
?>
