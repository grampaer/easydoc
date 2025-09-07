<?php
// Configuration de la base de données MariaDB
$host = 'localhost';
$dbname = 'easydb';
$username = 'easyuser';
$password = 'easypassword';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la table Users si elle n'existe pas
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS Users (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        UserName VARCHAR(255) UNIQUE NOT NULL,
        Email VARCHAR(255) UNIQUE NOT NULL,
        Password TEXT NOT NULL,
        FirstName VARCHAR(255),
        LastName VARCHAR(255),
        INAME VARCHAR(255),
        Profile_ID INT DEFAULT 1,
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
    ");

    // Création de la table Interventions
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS Interventions (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        User_ID INT NOT NULL,
        NISS TEXT,
        Nom TEXT,
        Prenom TEXT,
        Naissance DATE,
        Sexe TEXT,
        NumPatient TEXT,
        DateOp DATE,
        Anesthesie TEXT,
        TypeAnesthesie TEXT,
        Assistants TEXT,
        Infirmiers TEXT,
        Internes TEXT,
        TypeInterv TEXT,
        Cote TEXT,
        MembreOpere TEXT,
        CodesINAMI TEXT,
        NbSemaines TEXT,
        ArrondirDimanche TEXT,
        DateDebutIncap DATE,
        DateFinIncap DATE,
        NbSeancesKine INT,
        FrequenceSeancesKine TEXT,
        ConsignesKine TEXT,
        MatPansements BOOLEAN,
        MatIsobetadine BOOLEAN,
        MatChlorexidine BOOLEAN,
        MatCompresses BOOLEAN,
        SoinsInfirmiers TEXT,
        FrequenceSoins TEXT,
        DescriptionSoins TEXT,
        DureeSoins TEXT,
        MedParacetamol BOOLEAN,
        MedIbuprofene BOOLEAN,
        MedTradonal BOOLEAN,
        Imagerie1Quand TEXT,
        Imagerie1Type TEXT,
        Imagerie1InfoClinique TEXT,
        Imagerie1DemandeDiag TEXT,
        Imagerie1MembreCote TEXT,
        Imagerie2Quand TEXT,
        Imagerie2Type TEXT,
        Imagerie2InfoClinique TEXT,
        Imagerie2DemandeDiag TEXT,
        Imagerie2MembreCote TEXT,
        PriseAppui TEXT,
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (User_ID) REFERENCES Users(ID)
    )
    ");

    // Création de la table Presets
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS Presets (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Type VARCHAR(255) NOT NULL,
        Nom TEXT,
        Prenom TEXT,
        Naissance DATE,
        NISS TEXT,
        NumPatient TEXT,
        Sexe TEXT,
        TypeInterv TEXT,
        User_ID INT,
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (User_ID) REFERENCES Users(ID)
    )
    ");

    // Création de la table InterventionTemplates
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS InterventionTemplates (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        Type VARCHAR(255) UNIQUE NOT NULL,
        Name VARCHAR(255) NOT NULL,
        Data TEXT NOT NULL,
        CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
    ");

    // Insérer quelques presets par défaut
    $presets = [
        ['PTG', 'Dupont', 'Jean', '1980-05-15', '80051512345', 'PAT001', 'Masculin', 'Prothèse totale de genou'],
        ['PTH', 'Martin', 'Marie', '1975-08-20', '75082054321', 'PAT002', 'Féminin', 'Prothèse totale de hanche'],
        ['LCA', 'Dubois', 'Pierre', '1990-12-10', '90121098765', 'PAT003', 'Masculin', 'Ligament croisé antérieur'],
        ['Menisque', 'Leroy', 'Sophie', '1985-03-25', '85032545678', 'PAT004', 'Féminin', 'Ménisque']
    ];

    foreach ($presets as $preset) {
        $stmt = $pdo->prepare("INSERT INTO Presets (Type, Nom, Prenom, Naissance, NISS, NumPatient, Sexe, TypeInterv) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($preset);
    }

    echo "Base de données MariaDB initialisée avec succès!";
} catch (PDOException $e) {
    die("Erreur: " . $e->getMessage());
}
?>
