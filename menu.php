<div class="menu">
     <input type="submit" id="menu-templates" class="menu-item" onclick="loadTemplates(<?php echo $_SESSION['user_id']?>)" value="Templates" />
     <input type="submit" id="menu-historics" class="menu-item" onclick="loadHistorics(<?php echo $_SESSION['user_id']?>)" value="Historiques" />
     <input type="button" id="menu-statistics" class="menu-item" onclick="loadStatistics(<?php echo $_SESSION['user_id']?>)" value="Statistiques" />
</div>
<div class="menu">
     <input type="submit" id="menu-parameters" class="menu-item" onclick="loadParameters(<?php echo $_SESSION['user_id']?>)" value="Parameters" />
</div>
