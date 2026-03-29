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
(8, 'Scuderia Ferrari', 'Maranello', 'Analyse de données et machine learning.', 'contact@ferrari.fr', '0102030412'),
(9, 'Mercedes AMG Petronas', 'Brackley', 'Développement de logiciels embarqués pour la F1.', 'contact@mercedes-amg-petronas.fr', '0102030413'),
(10, 'Meta AI', 'Nice', 'Recherche en intelligence artificielle et développement de produits.', 'contact@meta.ai', '0102030414'),
(11, 'Amazon Web Services', 'Paris', 'Services cloud et infrastructure mondiale.', 'contact@aws.com', '0102030415'),
(12, 'IBM', 'Lyon', 'Technologies de l\'information et services cloud.', 'contact@ibm.fr', '0102030416'),
(13, 'Salesforce', 'Bordeaux', 'Solutions CRM et cloud computing.', 'contact@salesforce.com', '0102030417'),
(14, 'SAP', 'Lille', 'Logiciels de gestion d\'entreprise et solutions cloud.', 'contact@sap.com', '0102030418');

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
-- OFFRES
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
(30, 'Développeur Web3 / Blockchain', 'Exploration de solutions de smart contracts pour la traçabilité.', 1400.00, 6, '2026-07-24', 12);

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
(13, 'Vue.js'),
(14, 'Go'),
(15, 'Flutter'),
(16, 'Swift/Kotlin'),
(17, 'NLP'),
(18, 'DevOps'),
(19, 'Scrum Master'),
(20, 'Virtualisation & Cloud'),
(21, 'Business Analysis'),
(22, 'Web3 / Blockchain');

-- ─────────────────────────────────────────
-- LIENS OFFRE ↔ COMPETENCES
-- ─────────────────────────────────────────
INSERT INTO offre_competences (id_offre, id_competence) VALUES
(1, 1), (1, 5),        -- PHP + SQL
(2, 2), (2, 4),        -- JS + React
(3, 8),                -- Réseau
(4, 5), (4, 6),        -- SQL + Java
(5, 7), (5, 3),        -- Cyber + Python
(6, 12),               -- Mobile
(7, 9), (7, 4),        -- Data Science + React
(8, 10),               -- Admin Système
(9, 11),               -- Gestion de Projet
(10, 1), (10, 12),     -- PHP + Mobile
(11, 12),              -- Mobile
(12, 9), (12, 17),     -- Data Science + NLP
(13, 20),              -- Virtualisation & Cloud
(14, 11),              -- Gestion de Projet
(15, 1), (15, 4), (15, 18),   -- PHP + React + DevOps
(16, 12),              -- Mobile
(17, 9), (17, 17),     -- Data Science + NLP
(18, 7), (18, 8),      -- Cybersécurité + Réseau
(19, 11),             -- Gestion de Projet
(20, 4), (20, 13),    -- React + Vue.js
(21, 12),             -- Mobile
(22, 9), (22, 17),    -- Data Science + NLP
(23, 18),             -- DevOps
(24, 19),             -- Scrum Master
(25, 14), (25, 3),    -- Go + Python
(26, 12),             -- Mobile
(27, 5), (27, 9),     -- SQL + Data Science
(28, 20),            -- Virtualisation & Cloud
(29, 21),            -- Business Analysis
(30, 22);            -- Web3 / Blockchain

-- ─────────────────────────────────────────
-- WISHLISTS
-- ─────────────────────────────────────────
INSERT INTO wishlists (id_etudiant, id_offre) VALUES
(2, 1),
(2, 2),
(4, 3),
(5, 5),
(7, 7),
(8, 8),
(9, 16),
(10, 14),
(11, 19),
(12, 20),
(13, 22),
(14, 25),
(15, 27),
(16, 28),
(17, 30);

-- ─────────────────────────────────────────
-- CANDIDATURES
-- ─────────────────────────────────────────
INSERT INTO candidatures (id_etudiant, id_offre, cv, lettre_motivation, date_candidature) VALUES
(2, 1, 'cv_marie.pdf', 'Motivée pour le poste PHP.', '2026-03-02'),
(4, 2, 'cv_lucas.pdf', 'Passionné de frontend.', '2026-03-06'),
(5, 5, 'cv_emma.pdf', 'Très intéressée par la cybersécurité.', '2026-03-21'),
(7, 13, 'cv_charles.pdf', 'Marre de vroum vroum', '2026-03-31');

-- ─────────────────────────────────────────
-- EVALUATIONS
-- ─────────────────────────────────────────
INSERT INTO evaluations (id_entreprise, id_etudiant, note, commentaire, date_evaluation) VALUES
(1, 2, 5, 'Excellente entreprise', '2026-03-10'),
(2, 4, 4, 'Bon environnement', '2026-03-12'),
(5, 5, 5, 'Horrible et nul', '2026-03-25'),
(14, 16, 4, 'Très bonne expérience de stage', '2026-04-01'),
(14, 2, 5, 'Stage très enrichissant chez SAP', '2026-04-15'),
(14, 7, 4, 'Bonne expérience globale avec SAP', '2026-04-20');