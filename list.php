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

<div id="add-template">
    <form method="post">
    <label><b>Templated</b></label>
    <input type="text" placeholder="Enter template name" name="template_name" required>
    <input type="hidden" name="add-template">
    <button type="submit">Add</button>
    </form>
    </div>

?>
