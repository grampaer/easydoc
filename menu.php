<div class="menu">
     <input type="submit" id="menu-templates" class="item-menu" onclick="loadTemplates(<?php echo $_SESSION['user_id']?>)" value="Templates" />
     <input type="submit" id="menu-historics" class="item-menu" onclick="loadHistorics(<?php echo $_SESSION['user_id']?>)" value="Historiques" />
     <input type="button" id="menu-statistics" class="item-menu" onclick="loadStatistics(<?php echo $_SESSION['user_id']?>)" value="Statistiques" />
</div>
<div class="menu">
     <input type="submit" id="menu-parameters" class="item-menu" onclick="loadParameters(<?php echo $_SESSION['user_id']?>)" value="Parameters" />
</div>
