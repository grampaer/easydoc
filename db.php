<?php

class MyDB extends SQLite3
{    
    function __construct()
    {
        $this->open('easydb.db');
    }

    function getNextRow($result)
    {
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row;
    }

    function getNumColumns($result)
    {
        $num = $result->numColumns();
        return $num;        
    }
    
    function finalize($result)
    {
        $result->finalize();
    }
        
    function checkUser(string $username, string $password)
    {
        $statement = $this->prepare('SELECT * FROM Users WHERE username = :username and password = :password');
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);
        $result = $statement->execute();
        return $result;
    }

    function insertUser(string $username, string $password)
    {
        $statement = $this->prepare('INSERT Into Users (username , password) values (:username, :password)');
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);
        $result = $statement->execute();
        return $result;
    }
    
    function getTemplates(int $user_id)
    {
        $statement = $this->prepare('SELECT * FROM Templates WHERE User_ID = :user_id');
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();
        return $result;
    }
    
    function getSections(int $template_id, int $user_id)
    {
        $statement = $this->prepare('SELECT * FROM Sections WHERE Template_ID = :template_id and User_ID = :user_id');
        $statement->bindValue(':template_id', $template_id);
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();        
        return $result;
    }

    function getFields(int $section_id, int $template_id, int $user_id)
    {
        $statement = $this->prepare('SELECT * FROM Fields WHERE Template_ID = :template_id and Section_ID = :section_id  and User_ID = :user_id');
        $statement->bindValue(':template_id', $template_id);
        $statement->bindValue(':section_id', $section_id);
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();        
        return $result;
    }
    function insertTemplate(int $user_id, string $name)
    {
        $statement = $this->prepare('INSERT Into Templates (User_ID , Name) values (:user_id, :name)');
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':name', $name);
        $result = $statement->execute();        
        return $result;
    }
    
    function insertSection(int $template_id, int $user_id, string $name)
    {
        $statement = $this->prepare('INSERT Into Sections (User_ID, Template_ID, Name) values (:user_id, :template_id, :name)');
        $statement->bindValue(':template_id', $template_id);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':name', $name);
        $result = $statement->execute();        
        return $result;
    }

    function insertField(int $section_id, int $template_id, int $user_id, string $name, string $default_value)
    {
        $statement = $this->prepare('INSERT Into Fields ( User_ID, Template_ID, Section_ID, Name , Default_Value) values ( :user_id , :template_id , :section_id , :name , :default_value )');
        $statement->bindValue(':template_id', $template_id);
        $statement->bindValue(':section_id', $section_id);
        $statement->bindValue(':default_value', $default_value);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':name', $name);
        $result = $statement->execute();        
        return $result;
    } 
}

//Create objectdb
$db = new MyDB();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $_SESSION['logged']=false;
    
    if ($res = $db->checkUser($username, $password)) {
        $row = $db->getNextRow($res);
        if (isset($row['ID'])) {
            $_SESSION['logged']=true;
            $_SESSION['user_id']=$row['ID'];
        }
    }
    $db->finalize($res);
}

//Data functions
if (isset($_POST['add-template']) && isset($_POST['template_name'])) {
    $db->insertTemplate($_SESSION['user_id'],$_POST['template_name']);
    $db->finalize($res);
}
elseif (isset($_POST['add-section']) && isset($_POST['template_id']) && isset($_POST['section_name'])) {
    $db->insertSection($_POST['template_id'],$_SESSION['user_id'],$_POST['section_name']);
    $db->finalize($res);
}
elseif (isset($_POST['add-field']) && isset($_POST['template_id']) && isset($_POST['section_id']) && isset($_POST['field_name'])) {
    $db->insertField($_POST['section_id'],$_POST['template_id'],$_SESSION['user_id'],$_POST['field_name'],$_POST['field_default']);
    $db->finalize($res);
}
elseif (isset($_POST['select-template'])) {
    $_SESSION['template_id'] = $_POST['select-template'];
}


?>
