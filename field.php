<div>
    <div class="item-field-name"> <?php echo $field['Name'] ?> </div>
     <div class="item-field-value">
     <form method="post">
     <?php 
     switch ($field['Type_Value']) {
     case 0:
         ?>
         <textarea id="field_default"><?php echo $field['Default_Value'] ?></textarea>
         <?php
         break;
     case 1:
         ?>
         <input type="date" id="field_default" value="<?php echo $field['Default_Value'] ?>">
         <?php
         break;
     case 2:
         ?>
         <input type="text" id="field_default" value="<?php echo $field['Default_Value'] ?>">
         <?php
         break;
     default:
         ?>
         <input type="text" id="field_default" value="<?php echo $field['Default_Value'] ?>">
         <?php
         break;
     }
     ?>
     <button type="submit">Save</button>
     <button type="submit">Del</button>
     </form>
     </div>
     </div>
    
