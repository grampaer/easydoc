<div class="item-section-parameters">
     <div class="item-section-parameters-name"> <?php echo $section['Name'] ?>
     </div>
     <div class="item-section-parameters-commands">
     <i class="fa-solid fa-trash-can" onclick="removeSectionParameters(<?php echo $section['Template_ID'] ?>, <?php echo $section['ID'] ?>)"></i>
     </div>
     </div>
<?php
     $fields = $db->getParameters($section['ID']);
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

    $types = $db->getTypes($_SESSION['user_id']);
while ($type = $db->getNextRow($types)) {
    ?>
    <option id="option_<?php echo $type['ID'] ?>" value="<?php echo $type['ID'] ?>" data-type="<?php echo $type['Type'] ?>" ><?php echo $type['Name'] ?></option>
            
<?php
        }
?>
    </select>
    
        <button type="submit" class="item-field-value" onclick="addField(<?php echo $_GET['user_id'] ?>, <?php echo $template['ID'] ?>, <?php echo $section['ID'] ?>)">Add</button>
    </div>
