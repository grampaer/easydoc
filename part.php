<?php

session_start();

include_once("db.php");

// Generate list of parts / sections / fields
if(isset($_GET['part_id'])) {    
    $part['ID'] = $_GET['part_id'];
    $sections = $db->getParametersSections($part['ID']);
    while ($section = $db->getNextRow($sections)) {
        include("section_parameters.php");
    }
    $db->finalize($sections);
    ?>
        <div class="add-section-parameters">
             <div class="item-section-parameters">
             <input type="text" placeholder="Enter section name" id="add_section_name" required>
             <i class="fa-solid fa-plus" onclick="addParametersSection(<?php echo $part['ID'] ?>)"></i>
             </div>
             </div>
<?php
             }
?>
