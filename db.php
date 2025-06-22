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

    function getColumnsHis(int $user_id)
    {
        $statement = $this->prepare('SELECT HIS_Sections.Name HIS_Fields.Name FROM HIS_Sections LEFT JOIN HIS_Fields on HIS_Sections.ID = HIS_Fields.Section_ID LEFT JOIN HIS_Folders on HIS_Folders.ID = HIS_Sections.Folder_ID where User_ID = :user_id ');
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();
        return $result;
    }
    function getFoldersHis(int $user_id)
    {
        $statement = $this->prepare('SELECT * FROM HIS_Sections LEFT JOIN HIS_Fields on HIS_Sections.ID = HIS_Fields.Section_ID LEFT JOIN HIS_Folders on HIS_Folders.ID = HIS_Sections.Folder_ID where User_ID = :user_id ');
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();
        return $result;
    }
    
    function getTypes(int $user_id)
    {
        $statement = $this->prepare('SELECT * FROM Types WHERE User_ID = :user_id or User_ID IS NULL');
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();
        return $result;
    }

    function getOptionsType(int $type_id)
    {
        $statement = $this->prepare('SELECT * FROM Options_TYpes WHERE Type_ID = :type_id');
        $statement->bindValue(':type_id', $type_id);
        $result = $statement->execute();
        return $result;
    }
    
    function insertTypes(int $user_id, string $name, string $type, int $size)
    {
        $statement = $this->prepare('INSERT Into Templates (User_ID , Name, Type, Size) values (:user_id, :name , :type, :size)');
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':type', $type);
        $statement->bindValue(':size', $size);
        $result = $statement->execute();
        return $this->sqlite3_last_insert_rowid();
    }
    
    function finalize($result)
    {
        #$result->finalize();
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
        return $this->sqlite3_last_insert_rowid();
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
    
    if ($res = $db->checkUser
($username, $password)) {
        $row = $db->getNextRow($res);
        if (isset($row['ID'])) {
            $_SESSION['logged']=true;
            $_SESSION['user_id']=$row['ID'];
        }
    }
    $db->finalize($res);
}

//Data functions
if (isset($_GET['add-template']) && isset($_GET['template_name'])) {
    $id = $db->insertTemplate($_GET['user_id'],$_GET['template_name']);
    $db->finalize($res);
    echo $id;
}
elseif (isset($_GET['add-section']) && isset($_GET['template_id']) && isset($_GET['section_name'])) {
    $db->insertSection($_GET['template_id'],$_GET['user_id'],$_GET['section_name']);
    $db->finalize($res);
}
elseif (isset($_GET['add-field']) && isset($_GET['template_id']) && isset($_GET['section_id']) && isset($_GET['field_name'])) {
    $db->insertField($_GET['section_id'],$_GET['template_id'],$_GET['user_id'],$_GET['field_name'],$_GET['field_value']);
    $db->finalize($res);
}
elseif (isset($_GET['get-type-options']) && (isset($_GET['type_id']))) {
    $Options = $db->getOptionsType($_GET['type_id']);
    $db-finalize($res);
    echo json_encode($Options);
}
?>
