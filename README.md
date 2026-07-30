Vite & Gourmand

Application web développée en PHP, JavaScript et CSS, structurée selon une architecture en couches et conteneurisée avec Docker.

L'application permet aux visiteurs de consulter les menus proposés par l'entreprise Vite & Gourmand, de créer un compte, de commander un menu pour un événement et de suivre l'état de leur commande. Des espaces dédiés (Utilisateur, Employé, Administrateur) permettent la gestion des menus, des commandes et des statistiques.

Technologies
Back-end : PHP 8.2
Front-end : HTML5, CSS, JavaScript
Base de données relationnelle : MySQL 8.0
Base de données non relationnelle : MongoDB 7
Conteneurisation : Docker / Docker Compose
Gestion des dépendances : Composer (PHPMailer, MongoDB Driver, etc.)
Prérequis

Avant de commencer, assurez-vous d'avoir installé sur votre machine :

Docker Desktop (inclut Docker et Docker Compose)
Git

Aucune autre installation n'est nécessaire : PHP, MySQL et MongoDB tournent entièrement dans des conteneurs.

Installation en local
1. Cloner le dépôt
bash
git clone https://github.com/debbahline2-svg/vite-et-gourmand.git
cd vite-et-gourmand
2. Configurer les variables d'environnement

Copier le fichier d'exemple et renseigner vos propres valeurs :

bash
cp .env.example .env

Éditer le fichier .env et compléter les variables suivantes :

MYSQL_ROOT_PASSWORD=votre_mot_de_passe_mysql
MONGO_ROOT_PASSWORD=votre_mot_de_passe_mongo
SMTP_HOST=votre_serveur_smtp
SMTP_PORT=587
SMTP_USER=votre_identifiant_smtp
SMTP_PASS=votre_mot_de_passe_smtp
SMTP_FROM=contact@vite-et-gourmand.fr

⚠️ Le fichier .env ne doit jamais être commité (il est exclu via .gitignore).

3. Construire et lancer les conteneurs
bash
docker compose up -d --build

Cette commande va :

construire l'image PHP à partir du Dockerfile (PHP 8.2, extensions PDO, MySQLi et MongoDB)
démarrer le conteneur web (serveur PHP intégré)
démarrer le conteneur db-sql (MySQL 8.0) et importer automatiquement la structure de la base via init.sql
démarrer le conteneur db-nosql (MongoDB 7)
4. Vérifier que les conteneurs sont bien démarrés
bash
docker compose ps

Vous devez voir les trois services (web, db-sql, db-nosql) avec le statut Up.

5. Accéder à l'application

L'application est accessible à l'adresse :

http://localhost:8080
6. Accéder aux bases de données (optionnel)
MySQL : accessible depuis un client (DBeaver, MySQL Workbench) sur localhost:3309, base vite_et_gourmand
MongoDB : accessible depuis MongoDB Compass sur mongodb://root:votre_mot_de_passe_mongo@localhost:27018/?authSource=admin
7. Installer les dépendances PHP (si nécessaire)

Les dépendances sont normalement déjà présentes dans le dossier vendor/. En cas de besoin de les régénérer :

bash
docker compose exec web composer install
Arrêter l'application
bash
docker compose down

Pour supprimer également les volumes (et donc les données des bases) :

bash
docker compose down -v
Structure du projet
vite-et-gourmand/
├── css/                # Feuilles de style
├── images/             # Ressources images
├── includes/           # Classes et repositories (accès aux données, services métier)
│   ├── repositories/
│   └── services/
├── js/                 # Scripts JavaScript
├── pages/              # Pages PHP de l'application
├── vendor/             # Dépendances Composer (non versionné)
├── .env.example        # Modèle de variables d'environnement
├── docker-compose.yml  # Orchestration des conteneurs
├── Dockerfile          # Image PHP de l'application
├── init.sql            # Script de création et d'initialisation de la base MySQL
└── composer.json       # Dépendances PHP
Comptes de test

Voir le manuel d'utilisation (PDF fourni séparément) pour la liste des identifiants permettant de tester les parcours Visiteur, Utilisateur, Employé et Administrateur.