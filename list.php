<?php

include_once("db.php")

    ?>
    <div id="list">

<?php
    
    // Generate list
    $templates = $db->getTemplates($_GET['user_id']);
while ($template = $db->getNextRow($templates)) {
    ?> <div class="item-list-template" id="item-template_<?php echo $template['ID'] ?>">
        <input type="submit" onclick="loadTemplate(<?php echo $_GET['user_id'] ?> , <?php echo $template['ID'] ?>)" value="<?php echo $template['Name'] ?>">
        </div> <?php
        }
$db->finalize($templates);
?>
    
    <div class="add-template">
    <div class="item-list-template" >
    <input type="text" placeholder="Enter template name" id="add_template_name" required>
    <button type="submit" onclick="addTemplate(<?php echo $_GET['user_id'] ?>)">Add</button>
    </div>
    </div>
    </div>

    <div id="templates"></div>
    
