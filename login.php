<?php

if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $_SESSION['logged']=false;
    
    if ($res = $db->checkUser($username, $password)) {
        $row = $db->getNextRow($res);
        if (isset($row['ID'])) {
            $_SESSION['logged']=true;
            $_SESSION['user_id']=$row['ID'];
        }
    }
    $db->finalize($res);
}

if (!$_SESSION['logged']) { ?>
    <h1>Login</h1>			    
    <form method="post">
        <div class="container">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>
        
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>
        
            <button type="submit">Login</button>
            <label>
              <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
        </div>
        
        <div class="container" style="background-color:#f1f1f1">
            <button type="button" class="cancelbtn">Cancel</button>
            <span class="psw">Forgot <a href="#">password?</a></span>
        </div>
        <div class="container" style="background-color:#f1f1f1">
            <span class="signup"><a href="sign_up.php">Sign up</a></span>
        </div>
    </form>
	<?php 
    }
?>
