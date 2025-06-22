<?php
include_once("db.php");

// Generate list of parts / sections / fields
if(isset($_GET['part_id'])) {    
    $part['ID'] = $_GET['part_id'];
    $sections = $db->getParametersSections($part['ID'],$_GET['user_id']);
    while ($section = $db->getNextRow($sections)) {
        include("section_parameters.php");
    }
    $db->finalize($sections);
    ?>
        <div class="add-section-parameters">
             <div class="item-section-parameters">
             <input type="text" placeholder="Enter section name" id="add_section_name" required>
             <button type="submit" onclick="addParametersSection(<?php echo $_GET['user_id'] ?>, <?php echo $part['ID'] ?>)">Add</button>
             </div>
             </div>
<?php
             }
?>
