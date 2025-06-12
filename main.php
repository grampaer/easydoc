<?php

// Generate list of templates / sections / fields
if(isset($_SESSION['template_id'])) {
    $template['ID'] = $_SESSION['template_id'];
    $sections = $db->getSections($template['ID'],$_SESSION['user_id']);
    while ($section = $db->getNextRow($sections)) {
        ?> <div class="item-section"> <?php echo $section['Name'] ?> </div> <?php
        $fields = $db->getFields($section['ID'],$template['ID'],$_SESSION['user_id']);
        while ($field = $db->getNextRow($fields)) {
            ?>
            <div class="item-field-name"> <?php echo $field['Name'] ?> </div>
                <div class="item-field-value">
                <form method="post">
                <input type="text" name="field_default" value="<?php echo $field['Default_Value'] ?>">
                </form>
                </div> <?php
        }
        $db->finalize($fields);
        ?>
            <div id="add-field">
                 <form method="post">
                 <input type="text" placeholder="Enter field name" name="field_name" required>
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
        <div id="add-section">
             <form method="post">
             <input type="text" placeholder="Enter section name" name="section_name" required>
             <input type="hidden" name="template_id" value="<?php echo $template['ID'] ?>">             
             <input type="hidden" name="add-section">
             <button type="submit">Add</button>
             </form>
             </div>
<?php
             }
$db->finalize($templates);
    
?>
    
