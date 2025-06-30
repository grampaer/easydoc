<?php

if (!session_id()) {
    session_start();
}
     
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
        $statement = $this->prepare('SELECT HIS_Sections.Name, HIS_Fields.Name FROM HIS_Sections LEFT JOIN HIS_Fields on HIS_Sections.ID = HIS_Fields.Section_ID LEFT JOIN HIS_Folders on HIS_Folders.ID = HIS_Sections.Folder_ID where HIS_Folders.User_ID = :user_id');
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();
        return $result;
    }

    function getInformationHisByMonth(int $user_id)
    {
        // Calcule le nombre d’entrées par mois (année courante)
        $year = date("Y");
        $stmt = $pdo->prepare("
  SELECT strftime('%m', HIS_Folders.TimeStamp) AS month, COUNT(*) AS count
  FROM HIS_Sections LEFT JOIN HIS_Fields on HIS_Sections.ID = HIS_Fields.Section_ID LEFT JOIN HIS_Folders on HIS_Folders.ID = HIS_Sections.Folder_ID
  WHERE HIS_Folders.User_ID = :user_id' and strftime('%Y', HIS_Folders.TimeStamp) = :year
  GROUP BY month
");
        $stmt->execute([':year' => $year]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    function getInformationHis()
    {
        $pdo = new PDO("sqlite:./easydb.db");

        // Exécution de la requête
        $stmt = $pdo->query("SELECT * FROM HIS_Sections LEFT JOIN HIS_Fields on HIS_Sections.ID = HIS_Fields.Section_ID LEFT JOIN HIS_Folders on HIS_Folders.ID = HIS_Sections.Folder_ID where HIS_Folders.User_ID = " . $_SESSION['user_id']);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    
    function getFoldersHis(int $user_id)
    {
        $statement = $this->prepare('SELECT * FROM HIS_Sections LEFT JOIN HIS_Fields on HIS_Sections.ID = HIS_Fields.Section_ID LEFT JOIN HIS_Folders on HIS_Folders.ID = HIS_Sections.Folder_ID where User_ID = :user_id ');
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();
        return $result;
    }

    function getFieldsId(int $template)
    {
        $statement = $this->prepare('SELECT * FROM Fields where User_ID = :user_id and Template_ID = :template_id');
        $statement->bindValue(':template_id', $template);
        $statement->bindValue(':user_id', $_SESSION['user_id']);
        $result = $statement->execute();
        return $result;
    }

    function getParts(int $user_id)
    {
        $statement = $this->prepare('SELECT * FROM PARA_Parts WHERE User_ID = :user_id or User_ID IS NULL');
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

    function isUserExist(string $username, string $email)
    {
        $db = new PDO('sqlite:./easydb.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Vérification des doublons
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            return true;
        }
        return false;
    }
            
    function insertUser(string $username, string $email, string $password)
    {
        $db = new PDO('sqlite:./easydb.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertion en base
        $stmt = $db->prepare("INSERT INTO Users (UserName, Email, Password) VALUES (:username, :email, :password)");
        $result = $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        return $result;
    }

    function getTemplate(int $template_id): ?array
    {
        
        if (!isset($_SESSION['user_id'])) {
            throw new RuntimeException('Utilisateur non connecté');
        }
        
        $db = new PDO('sqlite:./easydb.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $db->prepare(
            'SELECT * FROM Templates WHERE ID = :template_id AND User_ID = :user_id'
    );
        
        $statement->bindValue(':template_id', $template_id, PDO::PARAM_INT);
        $statement->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $statement->execute();
        
        $template = $statement->fetch(PDO::FETCH_ASSOC);
        
        return $template ?: null;
    }

    public function getSections(int $template_id): array
    {
        if (!isset($_SESSION['user_id'])) {
            throw new RuntimeException('Utilisateur non connecté');
        }

        $db = new PDO('sqlite:./easydb.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        $stmt = $db->prepare("
        SELECT * FROM Sections
        WHERE Template_ID = :template_id AND User_ID = :user_id");

        $stmt->bindValue(':template_id', $template_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $sections ?: [];
    }
    
    public function insertFolder(int $template_id): int
    {

        if (!isset($_SESSION['user_id'])) {
            throw new RuntimeException('Utilisateur non connecté');
        }

        // Récupération du template
        $template = $this->getTemplate($template_id);
        if (!$template) {
            throw new RuntimeException("Template introuvable (ID: $template_id)");
        }
        
        $timestamp = date('Y-m-d H:i:s');
        
        $db = new PDO('sqlite:./easydb.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("
        INSERT INTO HIS_Folders (User_ID, Name, TimeStamp)
        VALUES (:user_id, :name, :timestamp)");

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $template['Name'], PDO::PARAM_STR);
        $stmt->bindValue(':timestamp', $timestamp, PDO::PARAM_STR);
        
        if (!$stmt->execute()) {
            throw new RuntimeException("Échec de l'insertion du dossier");
        }
        
         $folder_id =  $db->lastInsertId();

         $sections = $this->getSections($template_id);

         foreach ($sections as $section) {
          
             $stmt = $db->prepare("
        INSERT INTO HIS_Sections (Name, Folder_ID)
        VALUES (:name, :folder_id)");
         
             $stmt->bindValue('folder_id', $folder_id, PDO::PARAM_INT);
             $stmt->bindValue(':name', $section['Name'], PDO::PARAM_STR);
        
             if (!$stmt->execute()) {
                 throw new RuntimeException("Échec de l'insertion de la section");
             }

             $section_id =  $db->lastInsertId();

         }

         return $folder_id;
        
    }
    
    function finalize($result)
    {
        #$result->finalize();
    }
        
    function checkUser(string $username, string $password)
    {
        $db = new PDO('sqlite:./easydb.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM Users WHERE UserName = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['logged']=true;
            $_SESSION['user_id']=$user['ID'];
            
            return true;
        }
        else {
            $_SESSION['logged']=false;
            $_SESSION['user_id']=-1;
            
            return false;
        }
    }

    function removeField(int $user_id, int $field_id)
    {
        $pdo = new PDO('sqlite:./easydb.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = [
            'user_id' => $user_id,
            'field_id' => $field_id,
        ];
        
        $sql = "DELETE FROM Fields WHERE ID = :field_id AND User_ID = :user_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        return $stmt->rowCount();
    }

    function removeSection(int $user_id, int $section_id)
    {
        $pdo = new PDO('sqlite:./easydb.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = [
            'user_id' => $user_id,
            'section_id' => $section_id,
        ];
        
        $sql = "DELETE FROM Fields WHERE Section_ID = :section_id AND User_ID = :user_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        $sql = "DELETE FROM Sections WHERE ID = :section_id AND User_ID = :user_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        return $stmt->rowCount();
    }
    
    function removeTemplate(int $user_id, int $template_id)
    {
        $pdo = new PDO('sqlite:./easydb.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = [
            'user_id' => $user_id,
            'template_id' => $template_id,
        ];
        
        $sql = "DELETE FROM Fields WHERE Template_ID = :template_id AND User_ID = :user_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        $sql = "DELETE FROM Sections WHERE Template_ID = :template_id AND User_ID = :user_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        $sql = "DELETE FROM Templates WHERE ID = :template_id AND User_ID = :user_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        return $stmt->rowCount();
    }
    
    function getTemplates(int $user_id)
    {
        $statement = $this->prepare('SELECT * FROM Templates WHERE User_ID = :user_id');
        $statement->bindValue(':user_id', $user_id);
        $result = $statement->execute();
        return $result;
    }
   
    function getParametersSections(int $part_id)
    {
        $statement = $this->prepare('SELECT * FROM PARA_Sections WHERE Part_ID = :part_id');
        $statement->bindValue(':part_id', $part_id);
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

    function getParameters(int $section_id)
    {
        $statement = $this->prepare('SELECT * FROM PARA_Fields WHERE Section_ID = :section_id');
        $statement->bindValue(':section_id', $section_id);
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

    function insertPart(int $user_id, string $name)
    {
        $statement = $this->prepare('INSERT Into PARA_Parts (User_ID , Name) values (:user_id, :name)');
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

    function insertSectionParameters(int $part_id, string $name)
    {
        $statement = $this->prepare('INSERT Into PARA_Sections (Part_ID, Name) values (:part_id, :name)');
        $statement->bindValue(':part_id', $part_id);
        $statement->bindValue(':name', $name);
        $result = $statement->execute();        
        return $result;
    }

    function insertField(int $section_id, int $template_id, int $user_id, string $name, string $default_value, int $type)
    {
        $statement = $this->prepare('INSERT Into Fields ( User_ID, Template_ID, Section_ID, Name , Default_Value, Type_Value) values ( :user_id , :template_id , :section_id , :name , :default_value ,:type )');
        $statement->bindValue(':template_id', $template_id);
        $statement->bindValue(':section_id', $section_id);
        $statement->bindValue(':default_value', $default_value);
        $statement->bindValue(':type', $type);
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
    $db->checkUser($username, $password);
            
}

//Data functions
if (isset($_GET['add-template']) && isset($_GET['template_name'])) {
    $id = $db->insertTemplate($_GET['user_id'],$_GET['template_name']);
    $db->finalize($res);
    echo $id;
}
elseif (isset($_GET['add-section']) && isset($_GET['template_id']) && isset($_GET['section_name'])) {
    $db->insertSection($_GET['template_id'],$_SESSION['user_id'],$_GET['section_name']);
    $db->finalize($res);
}
elseif (isset($_GET['add-section-parameters']) && isset($_GET['part_id']) && isset($_GET['section_name'])) {
    $db->insertSectionParameters($_GET['part_id'],$_GET['section_name']);
    $db->finalize($res);
}
elseif (isset($_GET['add-field']) && isset($_GET['template_id']) && isset($_GET['section_id']) && isset($_GET['field_name'])) {
    $db->insertField($_GET['section_id'],$_GET['template_id'],$_SESSION['user_id'],$_GET['field_name'],$_GET['field_value'],$_GET['field_type']);
    $db->finalize($res);
}
elseif (isset($_GET['get-type-options']) && (isset($_GET['type_id']))) {
    $Options = $db->getOptionsType($_GET['type_id']);
    $db-finalize($res);
    echo json_encode($Options);
}
elseif (isset($_GET['add-part']) && isset($_GET['part_name'])) {
    $id = $db->insertPart($_GET['user_id'],$_GET['part_name']);
    $db->finalize($res);
    echo $id;
}
?>
