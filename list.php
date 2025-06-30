<?php

session_start();

include_once("db.php")

    ?>
    <div class="sidebar" id="list">
    <ul>
<?php
    
    // Generate list
    $templates = $db->getTemplates($_SESSION['user_id']);
while ($template = $db->getNextRow($templates)) {
    ?> <li class="item-list-template" id="item-template_<?php echo $template['ID'] ?>" onclick="loadTemplate(<?php echo $template['ID'] ?>)">
        <div class="item-list-name">
        <a><?php echo $template['Name'] ?></a>
        </div>
        <div class="item-list-commands">
        <i class="fa-solid fa-trash-can" onclick="removeTemplate(<?php echo $template['ID'] ?>)"></i>     
        </div>
        </li> <?php
        }
$db->finalize($templates);
?>
    </ul>
    
    <div class="add-template">
    <div class="item-list-template" >
    <input type="text" placeholder="Enter template name" id="add_template_name" required>
      <i class="fa-solid fa-plus" onclick="addTemplate(<?php echo $_SESSION['user_id'] ?>)"></i>
    </button>
    </div>
    </div>
    </div>

    <div id="templates"></div>
    
