<?php
$db = new SQLite3('easydb.db');

// Création de la table Users si elle n'existe pas
$db->exec("
CREATE TABLE IF NOT EXISTS Users (
    ID INTEGER PRIMARY KEY AUTOINCREMENT,
    UserName TEXT UNIQUE NOT NULL,
    Email TEXT UNIQUE NOT NULL,
    Password TEXT NOT NULL,
    FirstName TEXT,
    LastName TEXT,
    INAME TEXT,
    Profile_ID INTEGER DEFAULT 1,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP
)
");

// Création de la table Interventions
$db->exec("
CREATE TABLE IF NOT EXISTS Interventions (
    ID INTEGER PRIMARY KEY AUTOINCREMENT,
    User_ID INTEGER NOT NULL,
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
    NbSeancesKine INTEGER,
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
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (User_ID) REFERENCES Users(ID)
)
");

// Création de la table Presets
$db->exec("
CREATE TABLE IF NOT EXISTS Presets (
    ID INTEGER PRIMARY KEY AUTOINCREMENT,
    Type TEXT NOT NULL,
    Nom TEXT,
    Prenom TEXT,
    Naissance DATE,
    NISS TEXT,
    NumPatient TEXT,
    Sexe TEXT,
    TypeInterv TEXT,
    User_ID INTEGER,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (User_ID) REFERENCES Users(ID)
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
    $stmt = $db->prepare("INSERT INTO Presets (Type, Nom, Prenom, Naissance, NISS, NumPatient, Sexe, TypeInterv) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $preset[0], SQLITE3_TEXT);
    $stmt->bindValue(2, $preset[1], SQLITE3_TEXT);
    $stmt->bindValue(3, $preset[2], SQLITE3_TEXT);
    $stmt->bindValue(4, $preset[3], SQLITE3_TEXT);
    $stmt->bindValue(5, $preset[4], SQLITE3_TEXT);
    $stmt->bindValue(6, $preset[5], SQLITE3_TEXT);
    $stmt->bindValue(7, $preset[6], SQLITE3_TEXT);
    $stmt->bindValue(8, $preset[7], SQLITE3_TEXT);
    $stmt->execute();
}

echo "Base de données initialisée avec succès!";
?>
