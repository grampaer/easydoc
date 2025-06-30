<?php

include_once("db.php");

// Generate list of templates / sections / fields
if(isset($_GET['template_id'])) {    
    $template['ID'] = $_GET['template_id'];
    $sections = $db->getSections($template['ID']);
    foreach ($sections as $section) {
        include("section.php");
    }
    $db->finalize($sections);
    ?>
        <div class="add-section">
             <div class="item-section">
             <input type="text" placeholder="Enter section name" id="add_section_name" required>
             <i class="fa-solid fa-plus" onclick="addSection(<?php echo $template['ID'] ?>)"></i>
             </div>
             </div>
             <div class="item-template-commands">
             <i class="fa-solid fa-floppy-disk" onclick="saveFolder(<?php echo $template['ID'] ?>)"></i>
             </div>
<?php
             }
?>
