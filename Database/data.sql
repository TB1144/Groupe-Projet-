USE projet_web;

-- ─────────────────────────────────────────
-- NETTOYAGE DES TABLES
-- ─────────────────────────────────────────
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

-- ─────────────────────────────────────────
-- ENTREPRISES (1 à 20)
-- ─────────────────────────────────────────
INSERT INTO entreprises (id, nom, ville, description, email, telephone) VALUES
(1, 'Google', 'Paris', 'Moteur de recherche et services cloud mondiaux.', 'contact@google.fr', '0102030405'),
(2, 'Microsoft', 'Lyon', 'Éditeur de logiciels et services cloud Azure.', 'contact@microsoft.fr', '0102030406'),
(3, 'Orange', 'Bordeaux', 'Opérateur télécom et services numériques.', 'contact@orange.fr', '0102030407'),
(4, 'Capgemini', 'Lille', 'Conseil et services en transformation numérique.', 'contact@capgemini.fr', '0102030408'),
(5, 'Thales', 'Toulouse', 'Cybersécurité.', 'contact@thales.fr', '0102030409'),
(6, 'Sopra Steria', 'Nantes', 'Services de conseil et de transformation numérique.', 'contact@sopra-steria.fr', '0102030410'),
(7, 'Atos', 'Strasbourg', 'Services de transformation digitale et cloud.', 'contact@atos.fr', '0102030411'),
(8, 'Scuderia Ferrari', 'Maranello', 'Analyse de données et machine learning.', 'contact@ferrari.fr', '0102030412'),
(9, 'Mercedes AMG Petronas', 'Brackley', 'Développement de logiciels embarqués pour la F1.', 'contact@mercedes-amg-petronas.fr', '0102030413'),
(10, 'Meta AI', 'Nice', 'Recherche en intelligence artificielle et développement de produits.', 'contact@meta.ai', '0102030414'),
(11, 'Amazon Web Services', 'Paris', 'Services cloud et infrastructure mondiale.', 'contact@aws.com', '0102030415'),
(12, 'IBM', 'Lyon', 'Technologies de l\'information et services cloud.', 'contact@ibm.fr', '0102030416'),
(13, 'Salesforce', 'Bordeaux', 'Solutions CRM et cloud computing.', 'contact@salesforce.com', '0102030417'),
(14, 'SAP', 'Lille', 'Logiciels de gestion d\'entreprise et solutions cloud.', 'contact@sap.com', '0102030418'),
(15, 'Spotify', 'Paris', 'Plateforme de streaming musical et de podcasts.', 'jobs@spotify.fr', '0102030419'),
(16, 'Ubisoft', 'Montreuil', 'Développement et édition de jeux vidéo.', 'recrutement@ubisoft.com', '0102030420'),
(17, 'Doctolib', 'Nantes', 'Solutions e-santé et prise de rendez-vous.', 'rh@doctolib.fr', '0102030421'),
(18, 'Dassault Systèmes', 'Vélizy', 'Éditeur de logiciels de conception 3D.', 'contact@3ds.com', '0102030422'),
(19, 'Blizzard', 'Versailles', 'Développement de jeux vidéo et e-sport.', 'jobs@blizzard.com', '0102030423'),
(20, 'Red Bull Racing', 'Milton Keynes', 'Analyse de télémétrie et aéro-dynamique avancée.', 'data@redbullracing.com', '0102030424');

-- ─────────────────────────────────────────
-- USERS (1 à 30)
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
(15, 'Schumacher', 'Michael', 'michael.schumacher@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'pilote'),
(16, 'Senna', 'Ayrton', 'ayrton.senna@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(17, 'Prost', 'Alain', 'alain.prost@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(18, 'Hill', 'Damon', 'damon.hill@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(19, 'Mansell', 'Nigel', 'nigel.mansell@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(20, 'Piquet', 'Nelson', 'nelson.piquet@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(21, 'Lauda', 'Niki', 'niki.lauda@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(22, 'Brabham', 'Jack', 'jack.brabham@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(23, 'Clark', 'Jim', ' jim.clark@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(24, 'Hunt', 'James', 'james.hunt@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(25, 'Fangio', 'Juan Manuel', 'juan.manuel.fangio@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(26, 'Ascari', 'Alberto', 'alberto.ascari@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(27, 'Surtees', 'John', 'john.surtees@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(28, 'Piastri', 'Oscar', 'oscar.piastri@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(29, 'Russell', 'George', 'george.russell@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant'),
(30, 'Albon', 'Alexander', 'alexander.albon@web4all.fr', '$2y$10$0SlhHrfspBIBOnXybe.lxe635BSDLVr.foCM9PSH/KNoUtCXipBk2', 'etudiant');

-- ─────────────────────────────────────────
-- OFFRES (1 à 50)
-- ─────────────────────────────────────────
INSERT INTO offres (id, titre, description, remuneration, duree, date_offre, id_entreprise) VALUES
(1, 'Stage backend PHP', 'Stage backend PHP, API REST.', 1200.00, 6, '2026-03-01', 1),
(2, 'Développeur JavaScript', 'Frontend React/Vue.js.', 1500.00, 3, '2026-03-05', 2),
(3, 'Technicien Réseau', 'Administration réseau.', 1000.00, 4, '2026-03-10', 3),
(4, 'Consultant IT Junior', 'Conseil client.', 1300.00, 6, '2026-03-15', 4),
(5, 'Ingénieur Cybersécurité', 'Pentest et sécurité.', 1600.00, 6, '2026-03-20', 5),
(6, 'Stage en développement mobile', 'Développement d\'applications mobiles.', 1400.00, 6, '2026-03-25', 14),
(7, 'Stage en data science', 'Analyse de données et machine learning.', 1500.00, 6, '2026-03-30', 2),
(8, 'Stage en administration système', 'Gestion des serveurs et infrastructure.', 1200.00, 5, '2026-04-05', 3),
(9, 'Stage en gestion de projet IT', 'Coordination de projets informatiques.', 1300.00, 6, '2026-04-10', 4),
(10, 'Stage en développement web', 'Création de sites web et applications.', 1400.00, 4, '2026-04-15', 5),
(11, 'Stage en développement mobile', 'Développement d\'applications mobiles.', 1400.00, 6, '2026-04-20', 6),
(12, 'Data Scientist - IA Générative', 'Optimisation de LLM et analyse prédictive des comportements utilisateurs.', 1500.00, 6, '2026-04-25', 7),
(13, 'Ingénieur Système & Cloud', 'Migration d''infrastructures on-premise vers AWS et automatisation Terraform.', 1200.00, 1, '2026-04-30', 8),
(14, 'Chef de Projet IT (Agile)', 'Pilotage de sprints et coordination entre les équipes métier et technique.', 1300.00, 3, '2026-05-05', 9),
(15, 'Développeur Fullstack React/Node', 'Architecture et déploiement de nouvelles fonctionnalités sur une plateforme SaaS.', 1400.00, 5, '2026-05-10', 10),
(16, 'Développeur Mobile Flutter', 'Création d''interfaces fluides et intégration d''API temps réel pour iOS/Android.', 1400.00, 5, '2026-05-15', 11),
(17, 'Data Scientist - Computer Vision', 'Développement d''algorithmes de reconnaissance d''images pour la santé.', 1500.00, 6, '2026-05-20', 12),
(18, 'Administrateur Sécurité Réseaux', 'Audit de vulnérabilité et mise en place de politiques Zero Trust.', 1200.00, 4, '2026-05-25', 13),
(19, 'Product Owner Junior', 'Définition du backlog produit et analyse des besoins utilisateurs finaux.', 1300.00, 6, '2026-05-30', 1),
(20, 'Développeur Frontend Vue.js', 'Refonte de l''expérience utilisateur (UX) pour un site e-commerce majeur.', 1400.00, 2, '2026-06-04', 2),
(21, 'Développeur Mobile Swift/Kotlin', 'Optimisation des performances et implémentation du mode hors-ligne.', 1400.00, 2, '2026-06-09', 3),
(22, 'Data Scientist - NLP', 'Analyse de sentiments et classification automatique de documents juridiques.', 1500.00, 6, '2026-06-14', 4),
(23, 'DevOps & SRE', 'Mise en place de pipelines CI/CD et monitoring de clusters Kubernetes.', 1200.00, 1, '2026-06-19', 5),
(24, 'Scrum Master IT', 'Facilitation des rituels agiles et levée des obstacles techniques.', 1300.00, 6, '2026-06-24', 6),
(25, 'Développeur Backend Go/Python', 'Développement de microservices scalables pour le traitement de données.', 1400.00, 2, '2026-06-29', 7),
(26, 'Développeur Mobile React Native', 'Maintenance évolutive et refonte graphique d''une application sociale.', 1400.00, 2, '2026-07-04', 8),
(27, 'Data Engineer', 'Construction de pipelines ETL et gestion d''un data lake sous Snowflake.', 1500.00, 3, '2026-07-09', 9),
(28, 'Expert Virtualisation & Cloud', 'Gestion d''environnements hybrides VMWare et Azure.', 1200.00, 6, '2026-07-14', 10),
(29, 'IT Business Analyst', 'Interface entre les besoins business et les solutions techniques ERP.', 1300.00, 5, '2026-07-19', 11),
(30, 'Développeur Web3 / Blockchain', 'Exploration de solutions de smart contracts pour la traçabilité.', 1400.00, 6, '2026-07-24', 12),
(31, 'Développeur C++ / Moteur Physique', 'Optimisation du moteur de collision pour notre prochain AAA.', 1600.00, 6, '2026-08-01', 16),
(32, 'Ingénieur Data Santé', 'Traitement de données médicales massives.', 1550.00, 6, '2026-08-05', 17),
(33, 'DevOps Kubernetes', 'Gestion des clusters pour haute disponibilité.', 1400.00, 4, '2026-08-10', 15),
(34, 'Stage UI/UX Designer', 'Maquettage et prototypage de nouvelles interfaces sur Figma.', 1100.00, 3, '2026-08-15', 18),
(35, 'Télémétrie & Big Data', 'Analyse en temps réel des capteurs embarqués (F1).', 1800.00, 6, '2026-08-20', 20),
(36, 'Développeur Backend Rust', 'Création de micro-services ultra performants.', 1700.00, 6, '2026-08-25', 11),
(37, 'Développeur Fullstack', 'Maintenance de l''intranet RH et ajout de modules.', 1200.00, 2, '2026-09-01', 3),
(38, 'Expert Cybersécurité Cloud', 'Sécurisation des environnements AWS et Azure.', 1650.00, 6, '2026-09-05', 5),
(39, 'Stage Game Designer IT', 'Équilibrage des statistiques et intégration BDD.', 1300.00, 6, '2026-09-10', 19),
(40, 'Machine Learning Engineer', 'Création d''algorithmes de recommandation musicale.', 1750.00, 6, '2026-09-15', 15),
(41, 'Intégrateur Web Junior', 'HTML/CSS et un peu de JS pour des landing pages.', 1000.00, 2, '2026-09-20', 4),
(42, 'Architecte Logiciel', 'Conception d''architectures distribuées complexes.', 2000.00, 6, '2026-09-25', 2),
(43, 'Développeur iOS', 'Amélioration de l''application patient.', 1450.00, 4, '2026-10-01', 17),
(44, 'Technicien Support N2', 'Résolution d''incidents et gestion de parc.', 1100.00, 3, '2026-10-05', 6),
(45, 'Analyste SOC', 'Surveillance des réseaux et réponse aux incidents.', 1500.00, 5, '2026-10-10', 5),
(46, 'Développeur Blockchain', 'Création de smart contracts Ethereum.', 1600.00, 6, '2026-10-15', 1),
(47, 'Data Analyst Junior', 'Création de dashboards sous Tableau/PowerBI.', 1300.00, 4, '2026-10-20', 13),
(48, 'Stage R&D IA Embarquée', 'Optimisation d''IA pour systèmes à faible consommation.', 1700.00, 6, '2026-10-25', 10),
(49, 'Développeur Python/Django', 'Refonte du back-office de facturation.', 1350.00, 4, '2026-11-01', 14),
(50, 'Ingénieur Système Linux', 'Administration des serveurs RHEL et Debian.', 1400.00, 5, '2026-11-05', 12);

-- ─────────────────────────────────────────
-- COMPETENCES (1 à 28)
-- ─────────────────────────────────────────
INSERT INTO competences (id, nom) VALUES
(1, 'PHP'), (2, 'JavaScript'), (3, 'Python'), (4, 'React'), (5, 'SQL'), (6, 'Java'), 
(7, 'Cybersécurité'), (8, 'Réseau'), (9, 'Data Science'), (10, 'Administration Système'), 
(11, 'Gestion de Projet'), (12, 'Développement Mobile'), (13, 'Vue.js'), (14, 'Go'), 
(15, 'Flutter'), (16, 'Swift/Kotlin'), (17, 'NLP'), (18, 'DevOps'), (19, 'Scrum Master'), 
(20, 'Virtualisation & Cloud'), (21, 'Business Analysis'), (22, 'Web3 / Blockchain'),
(23, 'C++'), (24, 'Rust'), (25, 'Docker'), (26, 'Kubernetes'), (27, 'Figma'), (28, 'UI/UX Design');

-- ─────────────────────────────────────────
-- LIENS OFFRE ↔ COMPETENCES
-- ─────────────────────────────────────────
INSERT INTO offre_competences (id_offre, id_competence) VALUES
(1, 1), (1, 5), (2, 2), (2, 4), (3, 8), (4, 5), (4, 6), (5, 7), (5, 3), (6, 12), 
(7, 9), (7, 4), (8, 10), (9, 11), (10, 1), (10, 12), (11, 12), (12, 9), (12, 17), 
(13, 20), (14, 11), (15, 1), (15, 4), (15, 18), (16, 12), (17, 9), (17, 17), (18, 7), (18, 8), 
(19, 11), (20, 4), (20, 13), (21, 12), (22, 9), (22, 17), (23, 18), (24, 19), (25, 14), (25, 3), 
(26, 12), (27, 5), (27, 9), (28, 20), (29, 21), (30, 22), 
(31, 23), (32, 9), (32, 5), (33, 26), (33, 25), (34, 27), (34, 28), (35, 9), (35, 3), (36, 24), 
(37, 1), (37, 2), (38, 7), (38, 20), (39, 5), (40, 9), (40, 3), (41, 2), (42, 6), (42, 20), 
(43, 16), (44, 10), (45, 7), (45, 8), (46, 22), (47, 5), (47, 21), (48, 9), (48, 23), (49, 3), (50, 10), (50, 18);

-- ─────────────────────────────────────────
-- WISHLISTS (Grosse mise à jour : 100+ lignes)
-- ─────────────────────────────────────────
INSERT INTO wishlists (id_etudiant, id_offre) VALUES
(2, 1), (2, 2), (2, 38), (2, 15), (2, 42), (2, 50),
(3, 1), (3, 5), (3, 10), (3, 35),
(4, 3), (4, 43), (4, 20), (4, 26), (4, 12),
(5, 5), (5, 45), (5, 8), (5, 18), (5, 33),
(6, 2), (6, 7), (6, 12), (6, 40),
(7, 7), (7, 35), (7, 48), (7, 3), (7, 4), (7, 31),
(8, 8), (8, 40), (8, 42), (8, 2), (8, 4), (8, 30),
(9, 16), (9, 31), (9, 35), (9, 1), (9, 12), (9, 42),
(10, 14), (10, 43), (10, 8), (10, 11), (10, 21),
(11, 19), (11, 38), (11, 15), (11, 23), (11, 49),
(12, 20), (12, 39), (12, 1), (12, 5), (12, 16),
(13, 22), (13, 34), (13, 7), (13, 10), (13, 27),
(14, 25), (14, 50), (14, 9), (14, 32), (14, 47),
(15, 27), (15, 8), (15, 42), (15, 13),
(16, 28), (16, 36), (16, 1), (16, 33), (16, 46),
(17, 30), (17, 46), (17, 12), (17, 44), (17, 29),
(18, 41), (18, 2), (18, 22), (18, 37),
(19, 45), (19, 4), (19, 18), (19, 50),
(20, 44), (20, 6), (20, 46), (20, 11),
(21, 47), (21, 8), (21, 48), (21, 14),
(22, 33), (22, 11), (22, 49), (22, 25),
(23, 32), (23, 15), (23, 50), (23, 28),
(24, 37), (24, 18), (24, 1), (24, 19),
(25, 49), (25, 19), (25, 2), (25, 20),
(26, 35), (26, 21), (26, 3), (26, 24),
(27, 40), (27, 23), (27, 4), (27, 26),
(28, 42), (28, 25), (28, 5), (28, 29),
(29, 31), (29, 27), (29, 6), (29, 30),
(30, 36), (30, 29), (30, 7), (30, 32);

-- ─────────────────────────────────────────
-- CANDIDATURES (Grosse mise à jour avec les pilotes !)
-- ─────────────────────────────────────────
INSERT INTO candidatures (id_etudiant, id_offre, cv, lettre_motivation, date_candidature) VALUES
(2, 1, 'cv_adem_php.pdf', 'Motivé pour le poste PHP.', '2026-03-02'),
(2, 38, 'cv_adem_cyber.pdf', 'Motivé par le cloud et la sécurité.', '2026-09-07'),
(3, 10, 'cv_gabriel.pdf', 'Je code aussi vite que je roule.', '2026-03-18'),
(4, 2, 'cv_tarek_js.pdf', 'Passionné de frontend.', '2026-03-06'),
(4, 43, 'cv_tarek_ios.pdf', 'Passionné par l''écosystème Apple.', '2026-10-02'),
(5, 5, 'cv_misha_secu.pdf', 'Très intéressée par la cybersécurité.', '2026-03-21'),
(5, 45, 'cv_misha_soc.pdf', 'Prêt à surveiller le réseau H24.', '2026-10-11'),
(6, 12, 'cv_hugo_ia.pdf', 'L''IA c''est l''avenir des paddocks.', '2026-04-20'),
(7, 13, 'cv_charles_cloud.pdf', 'Marre de vroum vroum, je veux du cloud.', '2026-03-31'),
(7, 35, 'cv_charles_redbull.pdf', 'Je connais bien la télémétrie, prenez-moi chez Red Bull.', '2026-08-21'),
(7, 31, 'cv_charles_cpp.pdf', 'Je suis habitué aux crashs, le C++ ne me fait pas peur.', '2026-08-05'),
(8, 13, 'cv_lewis_proj.pdf', 'Expérience en gestion de projet et leadership.', '2026-04-06'),
(8, 40, 'cv_lewis_music.pdf', 'La musique et l''IA, c''est mon futur (en dehors de la mode).', '2026-09-16'),
(8, 30, 'cv_lewis_web3.pdf', 'Bono, my code is gone! Heureusement la blockchain garde tout.', '2026-07-25'),
(9, 14, 'cv_max_agile.pdf', 'Simply lovely management.', '2026-04-11'),
(9, 35, 'cv_max_data.pdf', 'Laissez-moi analyser ces datas à Milton Keynes.', '2026-08-22'),
(9, 42, 'cv_max_archi.pdf', 'Je veux être P1 sur le leaderboard des devs de l''entreprise.', '2026-09-28'),
(10, 16, 'cv_carlos_mob.pdf', 'Développement mobile passionnant.', '2026-04-16'),
(10, 11, 'cv_carlos_smooth.pdf', 'Smooth operator en dev mobile, mes applis ne crashent jamais.', '2026-04-22'),
(11, 17, 'cv_fernando_vision.pdf', 'Intéressé par la data science.', '2026-04-21'),
(11, 38, 'cv_fernando_cyber.pdf', 'Je défends les serveurs comme je défends ma position sur la piste.', '2026-09-06'),
(11, 23, 'cv_fernando_elplan.pdf', 'Trust El Plan : je vais optimiser vos clusters Kubernetes.', '2026-06-20'),
(12, 18, 'cv_lando_secu.pdf', 'Expérience en sécurité réseau.', '2026-04-26'),
(12, 39, 'cv_lando_gaming.pdf', 'Gros joueur, je veux coder des jeux et équilibrer les stats.', '2026-09-11'),
(12, 1, 'cv_lando_twitch.pdf', 'Si vous voulez, je peux streamer mon code sur Twitch pendant le stage.', '2026-03-05'),
(13, 19, 'cv_daniel_po.pdf', 'Compétences en gestion de projet.', '2026-05-01'),
(13, 34, 'cv_daniel_design.pdf', 'J''ai un grand sourire et un très bon sens de l''UX/UI.', '2026-08-16'),
(13, 10, 'cv_daniel_shoey.pdf', 'Je code toujours avec le sourire (et un shoey en cas de mise en prod réussie).', '2026-04-18'),
(14, 20, 'cv_seb_vue.pdf', 'Passionné par le frontend.', '2026-05-06'),
(14, 32, 'cv_seb_data.pdf', 'Je suis un inspecteur rigoureux du code, comme avec les monoplaces.', '2026-08-08'),
(15, 21, 'cv_michael_mob.pdf', 'Expérience en développement mobile.', '2026-05-11'),
(15, 42, 'cv_michael_boss.pdf', 'L''architecte logiciel, c''est moi. 7 fois champion du code.', '2026-09-27'),
(16, 22, 'cv_ayrton_nlp.pdf', 'Intéressé par la data science et le NLP.', '2026-05-16'),
(16, 33, 'cv_ayrton_k8s.pdf', 'Toujours à la limite de la performance sur les serveurs.', '2026-08-12'),
(17, 23, 'cv_alain_devops.pdf', 'Compétences en DevOps.', '2026-05-21'),
(17, 44, 'cv_alain_prof.pdf', 'On m''appelle le professeur, laissez-moi gérer votre parc informatique.', '2026-10-06'),
(18, 24, 'cv_damon_scrum.pdf', 'Expérience en gestion de projet agile.', '2026-05-26'),
(19, 25, 'cv_nigel_go.pdf', 'Compétences en Go et Python.', '2026-05-31'),
(20, 26, 'cv_nelson_react.pdf', 'Passionné par le développement mobile hybride.', '2026-06-05'),
(21, 47, 'cv_niki_data.pdf', 'L''analyse pure et dure. Zéro émotion, juste de la data.', '2026-10-21'),
(22, 49, 'cv_jack_python.pdf', 'Je construis mes propres scripts Python.', '2026-11-03'),
(23, 50, 'cv_jim_linux.pdf', 'Aussi fluide sur Linux que sur la piste.', '2026-11-06'),
(24, 1, 'cv_james_php.pdf', 'Je vis vite, je code vite en PHP.', '2026-03-04'),
(25, 2, 'cv_juan_js.pdf', 'L''expérience prime dans le JavaScript.', '2026-03-08'),
(26, 3, 'cv_alberto_net.pdf', 'Administration réseau au millimètre.', '2026-03-12'),
(27, 4, 'cv_john_it.pdf', 'Consultant IT aussi bon sur 2 roues que sur 4.', '2026-03-17'),
(28, 42, 'cv_oscar_archi.pdf', 'Jeune mais capable de concevoir des trucs très solides.', '2026-09-26'),
(28, 5, 'cv_oscar_cyber.pdf', 'Je garde mon sang-froid, parfait pour la cybersécurité.', '2026-03-22'),
(29, 31, 'cv_george_cpp.pdf', 'Mon C++ est aussi propre que mes trajectoires.', '2026-08-02'),
(29, 6, 'cv_george_pr.pdf', 'Je fais toujours des présentations PowerPoint parfaites.', '2026-03-28'),
(30, 36, 'cv_alex_rust.pdf', 'Le Rust, c''est robuste, comme ma défense.', '2026-08-26'),
(30, 7, 'cv_alex_ds.pdf', 'Je veux plonger dans les données pour comprendre pourquoi on est lent.', '2026-04-02');

-- ─────────────────────────────────────────
-- EVALUATIONS
-- ─────────────────────────────────────────
INSERT INTO evaluations (id_entreprise, id_etudiant, note, commentaire, date_evaluation) VALUES
(1, 2, 5, 'Excellente entreprise', '2026-03-10'),
(2, 4, 4, 'Bon environnement', '2026-03-12'),
(5, 5, 5, 'Horrible et nul', '2026-03-25'),
(14, 16, 4, 'Très bonne expérience de stage', '2026-04-01'),
(14, 2, 5, 'Stage très enrichissant chez SAP', '2026-04-15'),
(14, 7, 4, 'Bonne expérience globale avec SAP', '2026-04-20'),
(15, 8, 5, 'Super ambiance chez Spotify, locaux incroyables !', '2026-10-01'),
(20, 9, 5, 'L''analyse de data F1 est top niveau.', '2026-09-15'),
(20, 7, 3, 'Un peu trop de pression sur la data.', '2026-09-18'),
(16, 12, 4, 'Bons projets sur les jeux, mais horaires parfois longs.', '2026-11-01'),
(11, 30, 5, 'Beaucoup appris sur l''écosystème AWS.', '2026-09-10'),
(5, 11, 4, 'Très carré sur la sécurité, bonne méthode de travail.', '2026-11-05'),
(17, 10, 4, 'Techno récente et très utile pour la santé.', '2026-11-10'),
(19, 12, 5, 'Stage de rêve pour un gamer !', '2026-10-25'),
(3, 24, 2, 'Un peu trop "old school" dans la gestion de projet.', '2026-11-15'),
(18, 13, 5, 'Outils de maquettage super puissants.', '2026-10-05');

-- ─────────────────────────────────────────
-- MISE À JOUR nb_candidatures
-- ─────────────────────────────────────────
UPDATE offres SET nb_candidatures = (
    SELECT COUNT(*) FROM candidatures WHERE candidatures.id_offre = offres.id
);