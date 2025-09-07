<?php

if (!session_id()) {
    session_start();
}

class MyDB
{
    private $pdo;
    
    function __construct()
    {
        $host = 'localhost';
        $dbname = 'easydb';
        $username = 'easyuser';
        $password = 'easypassword';
        
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion: " . $e->getMessage());
        }
    }

    function getInformationHisByMonth(): array
    {
        // Récupérer l'ID de l'utilisateur connecté
        $user_id = $_SESSION['user_id'] ?? null;
        if (!$user_id) {
            return [];
        }
        
        // Requête pour compter les interventions par mois (adaptée pour MySQL)
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE_FORMAT(DateOp, '%Y-%m') as month,
                COUNT(*) as count
            FROM Interventions 
            WHERE User_ID = :user_id 
            GROUP BY DATE_FORMAT(DateOp, '%Y-%m')
            ORDER BY month
        ");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formater le résultat
        $monthlyData = [];
        foreach ($result as $row) {
            $monthlyData[$row['month']] = (int)$row['count'];
        }
        
        return $monthlyData;
    }
    
    function isUserExist(string $username, string $email): bool
    {
        // Vérification des doublons
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Users WHERE UserName = :username OR Email = :email");
        $stmt->execute([':username' => $username, ':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
            
    function insertUser(string $username, string $email, string $password, string $nom, string $prenom, string $specialite): bool
    {
        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $this->pdo->prepare("INSERT INTO Users (UserName, Email, Password, FirstName, LastName, INAME) 
                                        VALUES (:username, :email, :password, :prenom, :nom, :specialite)");
           
            return $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':prenom' => $prenom,
                ':nom' => $nom,
                ':specialite' => $specialite
            ]);
        } catch (PDOException $e) {
            error_log("Erreur insertUser: " . $e->getMessage());
            return false;
        }
    }
        
    function getInterventions(int $user_id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Interventions WHERE User_ID = :user_id ORDER BY CreatedAt DESC");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   
    function saveIntervention(array $data): bool
    {
        $sql = "INSERT INTO Interventions (
            User_ID, NISS, Nom, Prenom, Naissance, Sexe, NumPatient, DateOp, Anesthesie, 
            TypeAnesthesie, Assistants, Infirmiers, Internes, TypeInterv, Cote, MembreOpere, 
            CodesINAMI, NbSemaines, ArrondirDimanche, DateDebutIncap, DateFinIncap, 
            NbSeancesKine, FrequenceSeancesKine, ConsignesKine, MatPansements, MatIsobetadine, 
            MatChlorexidine, MatCompresses, SoinsInfirmiers, FrequenceSoins, DescriptionSoins, 
            DureeSoins, MedParacetamol, MedIbuprofene, MedTradonal, Imagerie1Quand, Imagerie1Type, 
            Imagerie1InfoClinique, Imagerie1DemandeDiag, Imagerie1MembreCote, Imagerie2Quand, 
            Imagerie2Type, Imagerie2InfoClinique, Imagerie2DemandeDiag, Imagerie2MembreCote, PriseAppui
        ) VALUES (
            :user_id, :niss, :nom, :prenom, :naissance, :sexe, :num_patient, :date_op, :anesthesie, 
            :type_anesthesie, :assistants, :infirmiers, :internes, :type_interv, :cote, :membre_opere, 
            :codes_inami, :nb_semaines, :arrondir_dimanche, :date_debut_incap, :date_fin_incap, 
            :nb_seances_kine, :frequence_seances_kine, :consignes_kine, :mat_pansements, :mat_isobetadine, 
            :mat_chlorexidine, :mat_compresses, :soins_infirmiers, :frequence_soins, :description_soins, 
            :duree_soins, :med_paracetamol, :med_ibuprofene, :med_tradonal, :imagerie1_quand, :imagerie1_type, 
            :imagerie1_info_clinique, :imagerie1_demande_diag, :imagerie1_membre_cote, :imagerie2_quand, 
            :imagerie2_type, :imagerie2_info_clinique, :imagerie2_demande_diag, :imagerie2_membre_cote, :prise_appui
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        // Convertir les valeurs booléennes en 0/1 pour SQLite
        $data['mat_pansements'] = isset($data['mat_pansements']) ? 1 : 0;
        $data['mat_isobetadine'] = isset($data['mat_isobetadine']) ? 1 : 0;
        $data['mat_chlorexidine'] = isset($data['mat_chlorexidine']) ? 1 : 0;
        $data['mat_compresses'] = isset($data['mat_compresses']) ? 1 : 0;
        $data['med_paracetamol'] = isset($data['med_paracetamol']) ? 1 : 0;
        $data['med_ibuprofene'] = isset($data['med_ibuprofene']) ? 1 : 0;
        $data['med_tradonal'] = isset($data['med_tradonal']) ? 1 : 0;
        
        return $stmt->execute($data);
    }

    function checkUser(string $username, string $password): bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE UserName = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['username'] = $user['UserName'];
            $_SESSION['logged'] = true;
            return true;
        }
        return false;
    }

    function getInterventionTemplates(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM InterventionTemplates ORDER BY Name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getInterventionTemplate(string $templateType): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM InterventionTemplates WHERE Type = :type");
        $stmt->execute([':type' => $templateType]);
        $template = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($template) {
            // Décoder les données JSON stockées
            $template['Data'] = json_decode($template['Data'], true);
            return $template;
        }
        return null;
    }
}

// Create database object
$db = new MyDB();

// Handle login if POST data is present
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($db->checkUser($username, $password)) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Identifiants invalides']);
        exit;
    }
}

?>
