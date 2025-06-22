<?php

include_once("db.php")

    ?>
    <div id="list_parameters">

<?php
    
    // Generate list
    $parts = $db->getParts($_GET['user_id']);
while ($part = $db->getNextRow($parts)) {
    ?> <div class="item-list-part" id="item-part_<?php echo $part['ID'] ?>">
        <input type="submit" onclick="loadPart(<?php echo $_GET['user_id'] ?> , <?php echo $part['ID'] ?>)" value="<?php echo $part['Name'] ?>">
        </div> <?php
        }
$db->finalize($parts);
?>
    
    <div class="add-part">
    <div class="item-list-part" >
    <input type="text" placeholder="Enter part name" id="add_part_name" required>
    <button type="submit" onclick="addPart(<?php echo $_GET['user_id'] ?>)">Add</button>
    </div>
    </div>
    </div>

    <div id="parts"></div>
    
