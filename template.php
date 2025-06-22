<?php
include_once("db.php");

// Generate list of templates / sections / fields
if(isset($_GET['template_id'])) {    
    $template['ID'] = $_GET['template_id'];
    $sections = $db->getSections($template['ID'],$_GET['user_id']);
    while ($section = $db->getNextRow($sections)) {
        include("section.php");
    }
    $db->finalize($sections);
    ?>
        <div class="add-section">
             <div class="item-section">
             <input type="text" placeholder="Enter section name" id="add_section_name" required>
             <button type="submit" onclick="addSection(<?php echo $_GET['user_id'] ?>, <?php echo $template['ID'] ?>)">Add</button>
             </div>
             </div>
             <button type="submit" onclick="saveFolder(<?php echo $_GET['user_id'] ?>, <?php echo $template['ID'] ?>)">Save</button>
             
<?php
             }
?>
