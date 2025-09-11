<?php

if (!session_id()) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['username']) || !isset($data['password'])) {
            throw new RuntimeException('Identifiants manquants');
        }

        include_once("db.php");
        $logged = $db->checkUser($data['username'], $data['password']);

        if ($logged) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Identifiants incorrects']);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

if (!$_SESSION['logged']) { ?>
    <div id="login">
        <div id="login-form">
        <form method="post">
        <div class="container">
        <div>
        <label for="uname"><b>Username</b></label>
                                <input type="text" placeholder="Enter Username" name="username" required>
                                </div>
                                <div>
                                <label for="psw"><b>Password</b></label>
                                                      <input type="password" placeholder="Enter Password" name="password" required>
                                                      </div>
                                                      <button type="submit">Login</button>
                                                      <label><input type="checkbox" checked="checked" name="remember"> Remember me</label>
                                                      </div>
                                                      <div class="container" style="background-color:#f1f1f1">
                                                      <span class="psw">Forgot <a href="#">password?</a></span>
                                                      </div>
                                                      <div class="container" style="background-color:#f1f1f1">
                                                      <span class="signup"><a href="signup.html">Sign up</a></span>
                                                      </div>
                                                      </form>
                                                      </div>
                                                      </div>
<?php 
        }
?>
