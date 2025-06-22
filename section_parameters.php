<div class="item-section-parameters"> <?php echo $section['Name'] ?>  </div>

<?php
     $fields = $db->getFields($section['ID'],$template['ID'],$_GET['user_id']);
while ($field = $db->getNextRow($fields)) {
    include("field.php");
}
$db->finalize($fields);
?>
    <div class="add-field">          
    <input class="item-field-name" type="text" placeholder="Enter field name" id="add_field<?php echo $section['ID'] ?>_name" required>
    <input type="date" class="item-field-value hidden" placeholder="enter default value" id="add_field<?php echo $section['ID'] ?>_value_date">
    <input type="text" class="item-field-value hidden" placeholder="enter default value" id="add_field<?php echo $section['ID'] ?>_value_1line">
    <textarea placeholder="enter default value" class="item-field-value" id="add_field<?php echo $section['ID'] ?>_value_text"></textarea>
    <select class="item-field-value hidden" id="add_field<?php echo $section['ID'] ?>_value_options"></select>
    
    <select id="add_field<?php echo $section['ID'] ?>_value_select" class="item-field-value" onchange="changeTypeField(<?php echo $section['ID'] ?>)" >
<?php

    $types = $db->getTypes($_GET['user_id']);
while ($type = $db->getNextRow($types)) {
    ?>
    <option id="option_<?php echo $type['ID'] ?>" value="<?php echo $type['ID'] ?>" data-type="<?php echo $type['Type'] ?>" ><?php echo $type['Name'] ?></option>
            
<?php
        }
?>
    </select>
    
        <button type="submit" class="item-field-value" onclick="addField(<?php echo $_GET['user_id'] ?>, <?php echo $template['ID'] ?>, <?php echo $section['ID'] ?>)">Add</button>
    </div>
