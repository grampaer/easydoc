<div id="item-field_<?php echo $field['ID'] ?>" class="item-field">
     <div class="item-field-name"> <?php echo $field['Name'] ?> </div>
     <div class="item-field-value">
<?php 
     switch ($field['Type_Value']) {
     case 0:
         ?>
         <textarea id="item-field_-<?php echo $field['ID'] ?>-value"><?php echo $field['Default_Value'] ?></textarea>
<?php
         break;
     case 1:
         ?>
         <input type="date" id="item-field_-<?php echo $field['ID'] ?>-value" value="<?php echo $field['Default_Value'] ?>">
<?php
         break;
     case 2:
         ?>
         <input type="text" id="item-field_-<?php echo $field['ID'] ?>-value" value="<?php echo $field['Default_Value'] ?>">
<?php
         break;
     default:
         ?>
         <input type="text" id="item-field_-<?php echo $field['ID'] ?>-value" value="<?php echo $field['Default_Value'] ?>">
<?php
         break;
     }
     ?>
         </div>
             <div class="item-field-commands">
     <i class="fa-solid fa-floppy-disk"></i>
     <i class="fa-solid fa-trash-can" onclick="removeField(<?php echo $field['Template_ID'] ?>, <?php echo $field['ID'] ?>)"></i>
     </div>
     </div>
    
