<?php

include_once("db.php");

header('Content-Type: application/json');

if (isset($_GET['template_id'])) {

    $res = $db->insertFolder($_GET['template_id']);
    $fields = $db->getFieldsId($_GET['template_id']);
    echo json_encode($fields);
        
}
elseif (isset($_GET['field_id']) && isset($_GET['field_value'])) {
    $res = $db->saveField($_SESSION['user_id'],$_GET['field_id'],$_GET['field_value']);
    echo json_encode($res);
    
}
    



