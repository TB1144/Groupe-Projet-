USE projet_web;

-- alors ca c'est juste pour vider les tables avant de les remplir à nouveau, ça évite les doublons
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE evaluations;
TRUNCATE TABLE candidatures;
TRUNCATE TABLE wishlists;
TRUNCATE TABLE offre_competences;
TRUNCATE TABLE offres;
TRUNCATE TABLE competences;
TRUNCATE TABLE users;
TRUNCATE TABLE entreprises;
SET FOREIGN_KEY_CHECKS = 1;
-- voila voila :3

-- ─────────────────────────────────────────
-- ENTREPRISES (déjà ok mais safe re-run)
-- ─────────────────────────────────────────
INSERT INTO entreprises (id, nom, ville, description, email, telephone) VALUES
(1, 'Google', 'Paris', 'Moteur de recherche et services cloud mondiaux.', 'contact@google.fr', '0102030405'),
(2, 'Microsoft', 'Lyon', 'Éditeur de logiciels et services cloud Azure.', 'contact@microsoft.fr', '0102030406'),
(3, 'Orange', 'Bordeaux', 'Opérateur télécom et services numériques.', 'contact@orange.fr', '0102030407'),
(4, 'Capgemini', 'Lille', 'Conseil et services en transformation numérique.', 'contact@capgemini.fr', '0102030408'),
(5, 'Thales', 'Toulouse', 'Cybersécurité.', 'contact@thales.fr', '0102030409'),
(6, 'Sopra Steria', 'Nantes', 'Services de conseil et de transformation numérique.', 'contact@sopra-steria.fr', '0102030410'),
(7, 'Atos', 'Strasbourg', 'Services de transformation digitale et cloud.', 'contact@atos.fr', '0102030411'),
(8, 'Scuderia Ferrari', 'Maranello', 'Analyse de données et machine learning.', 'contact@ferrari.fr', '0102030412');

-- ─────────────────────────────────────────
-- USERS
-- password = password123
-- ─────────────────────────────────────────
INSERT INTO users (id, nom, prenom, email, password, role) VALUES
(1, 'Admin', 'Web4All', 'admin@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'admin'),
(2, 'Brahim', 'Adem', 'adem.brahim@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(3, 'Butaud', 'Gabriel', 'gabriel.butaud@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'pilote'),
(4, 'Boughzala', 'Tarek', 'tarek.boughzala@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(5, 'Dopre', 'Misha', 'misha.dopre@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(6, 'Forato', 'Hugo', 'hugo.forato@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'pilote'),
(7, 'Leclerc', 'Charles', 'charles.leclerc@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(8, 'Hamilton', 'Lewis', 'lewis.hamilton@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(9, 'Verstappen', 'Max', 'max.verstappen@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(10, 'Sainz', 'Carlos', 'carlos.sainz@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(11, 'Alonso', 'Fernando', 'fernando.alonso@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(12, 'Norris', 'Lando', 'lando.norris@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(13, 'Ricciardo', 'Daniel', 'daniel.ricciardo@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(14, 'Vettel', 'Sebastian', 'sebastian.vettel@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(15, 'Schumacher', 'Michael', 'michael.schumacher@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'pilote');

-- ─────────────────────────────────────────
-- OFFRES
-- ─────────────────────────────────────────
INSERT INTO offres (id, titre, description, remuneration, duree, date_offre, id_entreprise) VALUES
(1, 'Stage backend PHP', 'Stage backend PHP, API REST.', 1200.00, 6, '2026-03-01', 1),
(2, 'Développeur JavaScript', 'Frontend React/Vue.js.', 1500.00, 3, '2026-03-05', 2),
(3, 'Technicien Réseau', 'Administration réseau.', 1000.00, 4, '2026-03-10', 3),
(4, 'Consultant IT Junior', 'Conseil client.', 1300.00, 6, '2026-03-15', 4),
(5, 'Ingénieur Cybersécurité', 'Pentest et sécurité.', 1600.00, 6, '2026-03-20', 5),
(6, 'Stage en développement mobile', 'Développement d\'applications mobiles.', 1400.00, 6, '2026-03-25', 1),
(7, 'Stage en data science', 'Analyse de données et machine learning.', 1500.00, 6, '2026-03-30', 2),
(8, 'Stage en administration système', 'Gestion des serveurs et infrastructure.', 1200.00, 6, '2026-04-05', 3),
(9, 'Stage en gestion de projet IT', 'Coordination de projets informatiques.', 1300.00, 6, '2026-04-10', 4),
(10, 'Stage en développement web', 'Création de sites web et applications.', 1400.00, 6, '2026-04-15', 5);

-- ─────────────────────────────────────────
-- COMPETENCES
-- ─────────────────────────────────────────
INSERT INTO competences (id, nom) VALUES
(1, 'PHP'),
(2, 'JavaScript'),
(3, 'Python'),
(4, 'React'),
(5, 'SQL'),
(6, 'Java'),
(7, 'Cybersécurité'),
(8, 'Réseau'),
(9, 'Data Science'),
(10, 'Administration Système'),
(11, 'Gestion de Projet'),
(12, 'Développement Mobile'),
(13, 'Vue.js');

-- ─────────────────────────────────────────
-- LIENS OFFRE ↔ COMPETENCES
-- ─────────────────────────────────────────
INSERT INTO offre_competences (id_offre, id_competence) VALUES
(1, 1), (1, 5),        -- PHP + SQL
(2, 2), (2, 4),        -- JS + React
(3, 8),                -- Réseau
(4, 5), (4, 6),        -- SQL + Java
(5, 7), (5, 3);        -- Cyber + Python

-- ─────────────────────────────────────────
-- WISHLISTS
-- ─────────────────────────────────────────
INSERT INTO wishlists (id_etudiant, id_offre) VALUES
(2, 1),
(2, 2),
(4, 3),
(5, 5);

-- ─────────────────────────────────────────
-- CANDIDATURES
-- ─────────────────────────────────────────
INSERT INTO candidatures (id_etudiant, id_offre, cv, lettre_motivation, date_candidature) VALUES
(2, 1, 'cv_marie.pdf', 'Motivée pour le poste PHP.', '2026-03-02'),
(4, 2, 'cv_lucas.pdf', 'Passionné de frontend.', '2026-03-06'),
(5, 5, 'cv_emma.pdf', 'Très intéressée par la cybersécurité.', '2026-03-21');

-- ─────────────────────────────────────────
-- EVALUATIONS
-- ─────────────────────────────────────────
INSERT INTO evaluations (id_entreprise, id_etudiant, note, commentaire, date_evaluation) VALUES
(1, 2, 5, 'Excellent candidat', '2026-03-10'),
(2, 4, 4, 'Bon travail', '2026-03-12'),
(5, 5, 5, 'Horrible et nul', '2026-03-25');