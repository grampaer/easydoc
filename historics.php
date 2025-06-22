<?php
include_once("db.php");
$columns = $db->getColumnsHis($_GET['user_id']); 
$folders = $db->getFoldersHis($_GET['user_id']); 

?>
    <h1>Historics</h1>
        <table>
<?php

$previous_name = "";
$line_sections = "";
$line_fields = "";
$line_filters = "";

while ($column = $db->getNextRow($columns)) {
    if (strcmp($column['HIS_Sections.Name'], $previous_name) !== 0) {
        $previous_name = $column['HIS_Sections.Name'];
        $line_sections .= "<td>".$previous_name."</td"; 
    }
    $line_fields .=  "<td>".$column['HIS_Sections.Name']." ".$column['HIS_Fields.Name']."</td>";
    $line_filters .= '<td><input type="text" placeholder="filter" id="filter" ></td>';
}
?>
    <tr>
<?php echo $line_sections ?>
        </tr>
            <tr>
<?php echo $line_fields ?>
            </tr>
            <tr>
<?php echo $line_filters ?>
            </tr>

            
            </table>
            
            
