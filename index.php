<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="./css/styles.css">
    </head>
	<body>
		<?php 
    		if (!session_id() ) {
		        session_start();
		        include("db.php");
		        include("login.php");
    		}
            
    		if ($_SESSION['logged']) {
                ?>
                <div id="content">
                    <div id="menu"><?php include("menu.php");?></div><div id="list"><?php include("list.php");?></div><div id="main"><?php include("main.php");?></div><div id="footer"><?php include("footer.php");?></div></div></div>
<?php } ?>
	</body>
</html>
