<?php
$current = basename($_SERVER['PHP_SELF']);
?>

    <nav class="menu">
    <div class="navbar">        
    <ul>
    <li><a id="menu-templates" class="menu-item" onclick="loadTemplates()">Templates</a></li>
    <li><a id="menu-historics" class="menu-item" onclick="loadHistorics()">Historiques</a></li>
    <li><a id="menu-statistics" class="menu-item" onclick="loadStatistics(<?php echo $_SESSION['user_id']?>)">Statistiques</a></li>
    </ul>
    </div>
    
    <div class="navbar">
    <ul>
    <li><a id="menu-parameters" class="menu-item" onclick="loadParameters(<?php echo $_SESSION['user_id']?>)">Parameters</a></li>
    <li><a id="menu-logout" class="menu-item" href="logout.php">Logout</a></li>
    </ul>
    </div>
    </nav>

