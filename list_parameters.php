<?php

session_start();

include_once("db.php")

    ?>
    <div class="sidebar" id="list_parameters">
    <ul>
<?php
    
    // Generate list
    $parts = $db->getParts($_SESSION['user_id']);
while ($part = $db->getNextRow($parts)) {
    ?> <li class="item-list-part" id="item-part_<?php echo $part['ID'] ?>">
        <a onclick="loadPart(<?php echo $part['ID'] ?>)"><?php echo $part['Name'] ?></a>
        </li><?php
        }
$db->finalize($parts);
?>
    </ul>
        
    <div class="add-part">
    <div class="item-list-part" >
    <input type="text" placeholder="Enter part name" id="add_part_name" required>
    <i class="fa-solid fa-plus" onclick="addPart(<?php echo $_SESSION['user_id'] ?>)"></i>
    </div>
    </div>
    </div>

    <div id="parts"></div>
    
