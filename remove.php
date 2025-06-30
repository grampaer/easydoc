<?php

session_start();
include_once("db.php");

if (isset($_GET['field_id'])) {
    $db->removeField($_SESSION['user_id'],$_GET['field_id']);
}
elseif (isset($_GET['section_id'])) {
    $db->removeSection($_SESSION['user_id'],$_GET['section_id']);
}
elseif (isset($_GET['template_id'])) {
    $db->removeTemplate($_SESSION['user_id'],$_GET['template_id']);
}
