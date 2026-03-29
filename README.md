# Groupe-Projet-
# Web4All - Plateforme de Recherche de Stages

Bienvenue sur le dépôt du projet Web4All ! Cette application web "From Scratch" a pour objectif de faciliter la recherche et la gestion des stages pour les étudiants, les pilotes de promotion et les administrateurs.

## Sommaire
- [Présentation](#présentation)
- [Fonctionnalités Principales](#fonctionnalités-principales)
- [Stack Technique](#stack-technique)
- [Prérequis & Installation](#prérequis--installation)
- [Architecture du Projet](#architecture-du-projet)
- [L'Équipe](#léquipe)

##  Présentation
L'application permet d'informatiser l'aide à la recherche de stages. Elle regroupe les offres de stage, stocke les données des entreprises partenaires et permet aux étudiants de postuler directement en ligne. 

##  Fonctionnalités Principales
- **Authentification & Autorisation** : Gestion des rôles (Étudiant, Pilote, Admin).
- **Gestion des Entreprises** : Création, modification, recherche et évaluation.
- **Gestion des Offres** : Ajout, recherche multicritères, statistiques (carrousel).
- **Candidatures** : Dépôt de CV/Lettres de motivation et suivi des postulations.
- **Wish-list** : Sauvegarde des offres intéressantes par les étudiants.

## Stack Technique

- **Frontend** : HTML5, CSS3 (Mobile-first, Responsive), JavaScript .
- **Backend** : PHP 8.x (Orienté Objet, respect des normes PSR-12).
- **Base de données** : MySQL .
- **Serveur** : Apache (avec configuration Vhost).
- **Tests** : PHPUnit.
- **Architecture** : MVC (Modèle - Vue - Contrôleur) avec un routeur personnalisé.

## Prérequis & Installation (Environnement Ubuntu/Linux)

### Prérequis
- Serveur LAMP (Linux, Apache, MySQL, PHP)
- [Composer](https://getcomposer.org/) (pour PHPUnit)
- Git

### Installation rapide
1. **Cloner le dépôt :**
   ```bash
   git clone (https://github.com/TB1144/Groupe-Projet-.git)

## Cahier des Charges - Suivi d'avancement

### Spécifications Fonctionnelles (SFx)
- [ ] **SFx 1** : Authentification et gestion des accès (Login/Logout, permissions par rôle).
- [ ] **SFx 2** : Rechercher et afficher une entreprise (critères multiples, offres liées, évaluations).
- [x] **SFx 3** : Créer une entreprise.
- [x] **SFx 4** : Modifier une entreprise.
- [ ] **SFx 5** : Évaluer une entreprise (pour les utilisateurs autorisés).
- [x] **SFx 6** : Supprimer une entreprise.
- [x] **SFx 7** : Rechercher et afficher une offre de stage.
- [x] **SFx 8** : Créer une offre de stage.
- [x] **SFx 9** : Modifier une offre de stage.
- [x] **SFx 10** : Supprimer une offre de stage.
- [ ] **SFx 11** : Consulter les statistiques des offres (Carrousel d'indicateurs clés).
- [x] **SFx 12** : Rechercher et afficher un compte Pilote.
- [x] **SFx 13** : Créer un compte Pilote.
- [x] **SFx 14** : Modifier un compte Pilote.
- [x] **SFx 15** : Supprimer un compte Pilote.
- [x] **SFx 16** : Rechercher et afficher un compte Étudiant.
- [x] **SFx 17** : Créer un compte Étudiant.
- [x] **SFx 18** : Modifier un compte Étudiant.
- [x] **SFx 19** : Supprimer un compte Étudiant.
- [x] **SFx 20** : Postuler à une offre (Upload CV et Lettre de Motivation).
- [x] **SFx 21** : Étudiant : Afficher la liste de ses candidatures (avec CV/LM).
- [x] **SFx 22** : Pilote : Afficher la liste des candidatures de ses élèves.
- [ ] **SFx 23** : Afficher les offres ajoutées à la wish-list.
- [ ] **SFx 24** : Ajouter une offre à la wish-list.
- [ ] **SFx 25** : Retirer une offre de la wish-list.
- [x] **SFx 27** : Pagination sur les affichages de listes (utilisateurs, entreprises, offres).
- [x] **SFx 28** : Mentions légales (conformité RGPD).
- [x] **Bonus** : Accès mobile PWA (Progressive Web App).

### Spécifications Techniques (STx)
- [x] **STx 1** : Architecture MVC obligatoire (Modèle - Vue - Contrôleur).
- [ ] **STx 2** : Conformité du code (HTML5 sémantique, W3C, CSS structuré, PHP POO, PSR-12).
- [ ] **STx 3** : Contrôle des champs de saisie des formulaires (Front HTML/JS et Back PHP).
- [x] **STx 4** : Interdiction d’utiliser les CMS (WordPress, etc.).
- [x] **STx 5** : Interdiction d'utiliser des Frameworks front/back (React, Symfony...). Autorisation SASS/jQuery.
- [x] **STx 6** : Stack technique (Serveur Apache, Front HTML5/CSS3/JS, Back PHP, BDD SQL).
- [ ] **STx 7** : Moteur de template (Inclusion de fragments de page côté backend).
- [x] **STx 8** : Utilisation de clés étrangères dans le SGBD.
- [x] **STx 9** : Vhost spécifique pour le contenu statique (dossier `public/`).
- [ ] **STx 10** : Responsive Design (Mobile first + Menu burger).
- [ ] **STx 11** : Sécurité (Cookies sécurisés, mots de passe hashés, failles SQL/XSS/CSRF, HTTPS).
- [ ] **STx 12** : Optimisation SEO (Balises meta, perfs < 3s, URL propres, sitemap.xml, robots.txt).
- [x] **STx 13** : Routage d'URL côté Backend (URLs lisibles et hiérarchisées).
- [ ] **STx 14** : Tests unitaires (PHPUnit sur au moins un contrôleur).
