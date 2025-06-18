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
     if (!$S_SESSION['logged']) {
         include_once("db.php");
         include("login.php");
     }
if ($_SESSION['logged']) {
    ?>
         <div id="content">
             <div id="menu"><?php include("menu.php");?></div><div id="main"></div><div id="footer"><?php include("footer.php");?></div></div></div>
<?php } ?>
    <script src="js/nav.js"></script>
         </body>
         </html>
