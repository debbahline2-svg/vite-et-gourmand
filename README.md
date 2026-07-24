HEAD
# Vite & Gourmand - Système de Gestion de Commandes

Projet de gestion de restauration rapide avec suivi des commandes, espace employé, et système d'avis clients.

##  Prérequis
- [XAMPP](https://www.apachefriends.org/fr/index.html) (avec PHP 8.x et MySQL)

##  Installation
1. **Cloner/Copier** le dossier du projet dans votre répertoire `htdocs` de XAMPP.
2. **Base de données** : 
   - Ouvrez `phpMyAdmin` (http://localhost/phpmyadmin).
   - Créez une base nommée `vite_et_gourmand`.
   - Importez le fichier `database.sql` 
3. **Configuration** :
   - Modifiez le fichier `includes/db.php` pour ajuster vos accès base de données 
4. **Lancement** :
   - Démarrez Apache et MySQL sur XAMPP.
   - Accédez à `http://localhost/vite-et-gourmand/` dans le navigateur

## 📋 Fonctionnalités clés
- **Admin/Employé** : Gestion complète des statuts de commande et modération des avis.
- **Client** : Commande en ligne, suivi en temps réel et notation après réception.
- **Logique métier** : Calcul des frais de livraison dynamique (5.59€/km hors Bordeaux).
- **Statistiques** : Analyse des ventes (MySQL) et statistiques NoSQL (MongoDB).

=======
# vite-et-gourmand
cd71e3e1b547daece12a227bd95e96aa81d39c14
