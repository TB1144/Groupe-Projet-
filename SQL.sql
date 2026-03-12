CREATE DATABASE IF NOT EXISTS projet_web;
USE projet_web;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','pilote','etudiant')
);

CREATE TABLE IF NOT EXISTS entreprises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    description TEXT,
    email VARCHAR(255),
    telephone VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS offres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    description TEXT,
    remuneration DECIMAL(8,2),
    date_offre DATE,
    entreprise_id INT,
    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id)
);

CREATE TABLE IF NOT EXISTS competences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS offre_competence (
    offre_id INT,
    competence_id INT,
    PRIMARY KEY (offre_id, competence_id),
    FOREIGN KEY (offre_id) REFERENCES offres(id),
    FOREIGN KEY (competence_id) REFERENCES competences(id)
);

CREATE TABLE IF NOT EXISTS candidatures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT,
    offre_id INT,
    cv VARCHAR(255),
    lettre_motivation TEXT,
    date_candidature DATE,
    FOREIGN KEY (etudiant_id) REFERENCES users(id),
    FOREIGN KEY (offre_id) REFERENCES offres(id)
);

CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT,
    offre_id INT,
    FOREIGN KEY (etudiant_id) REFERENCES users(id),
    FOREIGN KEY (offre_id) REFERENCES offres(id)
);