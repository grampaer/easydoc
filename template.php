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
             <form class="item-section" method="post">
             <input type="text" placeholder="Enter section name" name="section_name" required>
             <input type="hidden" name="template_id" value="<?php echo $template['ID'] ?>">             
             <input type="hidden" name="add-section">
             <button type="submit">Add</button>
             </form>
             </div>
