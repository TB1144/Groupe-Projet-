CREATE DATABASE IF NOT EXISTS projet_web;
USE projet_web;

-- ─────────────────────────────────────────
-- STRUCTURE
-- ─────────────────────────────────────────

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

-- ─────────────────────────────────────────
-- DONNÉES DE TEST
-- ─────────────────────────────────────────

-- Entreprises
INSERT INTO entreprises (nom, ville, description, email, telephone) VALUES
('Google', 'Paris', 'Moteur de recherche et services cloud mondiaux.', 'contact@google.fr', '0102030405'),
('Microsoft', 'Lyon', 'Éditeur de logiciels et services cloud Azure.', 'contact@microsoft.fr', '0102030406'),
('Orange', 'Bordeaux', 'Opérateur télécom et services numériques.', 'contact@orange.fr', '0102030407'),
('Capgemini', 'Lille', 'Conseil et services en transformation numérique.', 'contact@capgemini.fr', '0102030408'),
('Thales', 'Toulouse', 'Systèmes d\'information et cybersécurité.', 'contact@thales.fr', '0102030409');

-- Offres
INSERT INTO offres (titre, description, remuneration, duree, date_offre, id_entreprise) VALUES
('Développeur PHP', 'Stage PHP orienté backend, développement d\'API REST.', 1200.00, 6, '2026-03-01', 1),
('Développeur JavaScript', 'Stage frontend React/Vue.js, interfaces modernes.', 1500.00, 3, '2026-03-05', 2),
('Technicien Réseau', 'Stage en administration réseau et infrastructure.', 1000.00, 4, '2026-03-10', 3),
('Consultant IT Junior', 'Stage en conseil et accompagnement client.', 1300.00, 6, '2026-03-15', 4),
('Ingénieur Cybersécurité', 'Stage en analyse de vulnérabilités et tests d\'intrusion.', 1600.00, 6, '2026-03-20', 5);

-- Utilisateurs (mot de passe : "password123")
INSERT INTO users (nom, prenom, email, password, role) VALUES
('Admin', 'Web4All', 'admin@web4all.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Dupont', 'Marie', 'marie.dupont@viacesi.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant'),
('Martin', 'Pierre', 'pierre.martin@viacesi.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pilote');

-- Compétences
INSERT INTO competences (nom) VALUES
('PHP'), ('JavaScript'), ('Python'), ('React'), ('SQL'), ('Java'), ('Cybersécurité'), ('Réseau');
