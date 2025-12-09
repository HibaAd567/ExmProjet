
use hebaad_db;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe_hash VARCHAR(255) NOT NULL,
    role ENUM('DIRECTEUR', 'FORMATEUR', 'ADMIN') NOT NULL,
    actif BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE filieres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_filiere VARCHAR(50) NOT NULL UNIQUE,
    intitule VARCHAR(255) NOT NULL,
    secteur VARCHAR(100) NOT NULL,
    niveau ENUM('T', 'TS', 'S', 'Q') NOT NULL,
    type_formation VARCHAR(100) NOT NULL
);

CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filiere_id INT NOT NULL,
    numero INT NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    masse_horaire INT NOT NULL,
    FOREIGN KEY (filiere_id) REFERENCES filieres(id)
);


CREATE TABLE groupes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filiere_id INT NOT NULL,
    code_groupe VARCHAR(50) NOT NULL,
    annee_formation INT NOT NULL,
    FOREIGN KEY (filiere_id) REFERENCES filieres(id)
);



CREATE TABLE attributions_module (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    groupe_id INT NOT NULL,
    formateur_responsable_id INT NOT NULL,
    annee INT NOT NULL,
    semestre TINYINT NOT NULL,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    FOREIGN KEY (groupe_id) REFERENCES groupes(id) ON DELETE CASCADE,
    FOREIGN KEY (formateur_responsable_id) REFERENCES utilisateurs(id)
);


CREATE TABLE attributions_verification (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attribution_module_id INT NOT NULL,
    formateur_verificateur_id INT NOT NULL,
    date_affectation DATETIME NOT NULL,
    statut_verification ENUM('NON_DEPOSE', 'DEPOSE', 'EN_VERIFICATION', 'NON_CONFORME', 'CONFORME') NOT NULL,
    FOREIGN KEY (attribution_module_id) REFERENCES attributions_module(id),
    FOREIGN KEY (formateur_verificateur_id) REFERENCES utilisateurs(id)
);


CREATE TABLE dossiers_examens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attribution_verification_id INT NOT NULL,
    date_depot DATETIME NOT NULL,
    chemin_pv_notes VARCHAR(255),
    chemin_pv_presence VARCHAR(255) NOT NULL,
    chemin_feuilles_stagiaires VARCHAR(255) NOT NULL,
    chemin_epreuve VARCHAR(255) NOT NULL,
    commentaires_responsable TEXT,
    FOREIGN KEY (attribution_verification_id) REFERENCES attributions_verification(id)
);



CREATE TABLE checklists_verification (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attribution_verification_id INT NOT NULL,
    somme_ok BOOLEAN NOT NULL,
    report_notes_ok BOOLEAN NOT NULL,
    pv_presence_ok BOOLEAN NOT NULL,
    signatures_ok BOOLEAN NOT NULL,
    epreuve_ok BOOLEAN NOT NULL,
    commentaire_global TEXT,
    date_verification DATETIME NOT NULL,
    verifie_par_id INT NOT NULL,
    FOREIGN KEY (attribution_verification_id) REFERENCES attributions_verification(id),
    FOREIGN KEY (verifie_par_id) REFERENCES utilisateurs(id)
);


CREATE TABLE anomalies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attribution_verification_id INT NOT NULL,
    type_anomalie VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    severite ENUM('MINEURE', 'MAJEURE') NOT NULL,
    statut_traitement ENUM('A_CORRIGER', 'EN_COURS', 'CORRIGEE') NOT NULL,
    date_signalement DATETIME NOT NULL,
    date_resolution DATETIME DEFAULT NULL,
    resolue_par_formateur_id INT DEFAULT NULL,
    FOREIGN KEY (attribution_verification_id) REFERENCES attributions_verification(id) ,
    FOREIGN KEY (resolue_par_formateur_id) REFERENCES utilisateurs(id)
);


CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    type VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    lien VARCHAR(255) NOT NULL,
    date_creation DATETIME NOT NULL,
    lu BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);


show tables;