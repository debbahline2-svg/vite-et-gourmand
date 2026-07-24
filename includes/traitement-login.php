<?php
session_start();
// On corrige les chemins selon dossier 
require_once '../includes/database.php';
require_once '../includes/user.php';

// Connexion à la base de données
$database = new Database(); // REGARDE QUE C PAREIL classe dans db.php s'appelle Database
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // On récupère les données du formulaire
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];

    // Tentative de connexion
    $userData = $user->login();

    if ($userData) {
        // 1. On stocke les infos de base dans la SESSION
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_nom'] = $userData['nom'];
        $_SESSION['user_prenom'] = $userData['prenom'];
        $_SESSION['role'] = $userData['role'];

        // 2. attention : On gère la redirection selon le rôle
        if ($userData['role'] === 'admin') {
            // On crée la variable 'admin' attendue par ta page admin.php
            $_SESSION['admin'] = $userData['prenom']; 
            
            // On envoie Julie vers son tableau de bord
            header("Location: admin.php");
        } else {
            // On envoie les clients vers l'accueil
            header("Location: ../index.php");
        }
        exit();
        
    } else {
        // Si ça échoue, on revient au login avec un message d'erreur
        header("Location: login.php?erreur=1");
        exit();
    }
}
?>