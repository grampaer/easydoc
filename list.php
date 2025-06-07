<?php  
// Generate list
$templates = $db->getTemplates($_SESSION['user_id']);
while ($template = $db->getNextRow($templates)) {
    ?> <div class="item-list-template">
        <form method="post">
        <input type="hidden" name="select-template" value="<?php echo $template['ID'] ?>">
        <input type="submit" value="<?php echo $template['Name'] ?>">
        </form>
        </div> <?php
}
?>
