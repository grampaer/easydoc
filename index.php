<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="./css/styles.css">
    </head>
	<body>
		<?php 
     if (!session_id()) {
         session_start();
     }
     if (!isset($S_SESSION['logged']) || !$S_SESSION['logged']) {
         include_once("db.php");
         include("login.php");
     }
if ($_SESSION['logged']) {
    ?>
    <div id="content">
        <div id="menu"><?php include("menu.php");?></div><div id="main"></div><div id="footer"><?php include("footer.php");?></div></div></div>
<?php } ?>
    <script src="js/nav.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.com/libraries/Chart.js"></script>
         </body>
         </html>
