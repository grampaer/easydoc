<div class="item-section"> <?php echo $section['Name'] ?>  </div> <?php
     $fields = $db->getFields($section['ID'],$template['ID'],$_GET['user_id']);
while ($field = $db->getNextRow($fields)) {
    include("field.php");
}
        $db->finalize($fields);
?>
            <div class="add-field">
            <form method="post">
            <input class="item-field-name" type="text" placeholder="Enter field name" name="field_name" required>
            <input type="text" placeholder="enter default value" name="field_default" >
            <input type="hidden" name="template_id" value="<?php echo $template['ID'] ?>">
            <input type="hidden" name="section_id" value="<?php echo $section['ID'] ?>">
            <input type="hidden" name="add-field">
            <button type="submit">Add</button>
            </form>
                 </div>
<?php
            }
$db->finalize($sections);
        ?>
    <div class="add-section">
    <form class="item-section" method="post">
    <input type="text" placeholder="Enter section name" name="section_name" required>
    <input type="hidden" name="template_id" value="<?php echo $template['ID'] ?>">             
    <input type="hidden" name="add-section">
    <button type="submit">Add</button>
    </form>
    </div>
