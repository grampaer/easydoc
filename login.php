<?php

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
