CREATE DATABASE IF NOT EXISTS projet_web;
USE projet_web;

-- ─────────────────────────────────────────
-- STRUCTURE
-- ─────────────────────────────────────────

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','pilote','etudiant'),
    id_pilote INT NULL,
    FOREIGN KEY (id_pilote) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS entreprises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    description TEXT,
    ville VARCHAR(255),
    email VARCHAR(255),
    telephone VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS offres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    description TEXT,
    remuneration DECIMAL(8,2),
    duree INT,
    nb_candidatures INT DEFAULT 0,
    date_offre DATE,
    id_entreprise INT,
    FOREIGN KEY (id_entreprise) REFERENCES entreprises(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS competences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS offre_competences (
    id_offre INT,
    id_competence INT,
    PRIMARY KEY (id_offre, id_competence),
    FOREIGN KEY (id_offre) REFERENCES offres(id) ON DELETE CASCADE,
    FOREIGN KEY (id_competence) REFERENCES competences(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant INT,
    id_offre INT,
    FOREIGN KEY (id_etudiant) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_offre) REFERENCES offres(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS candidatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant INT,
    id_offre INT,
    cv VARCHAR(255),
    lettre_motivation TEXT,
    date_candidature DATE,
    FOREIGN KEY (id_etudiant) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_offre) REFERENCES offres(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_entreprise INT NOT NULL,
    id_etudiant INT NOT NULL,
    note TINYINT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_evaluation DATE DEFAULT (CURRENT_DATE),
    FOREIGN KEY (id_entreprise) REFERENCES entreprises(id) ON DELETE CASCADE,
    FOREIGN KEY (id_etudiant) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_eval (id_entreprise, id_etudiant)
);