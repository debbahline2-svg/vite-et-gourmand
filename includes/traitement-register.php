<?php
// Comme le fichier est déjà dans 'includes', on n'a plus besoin du '../'
require_once 'db.php'; 
require_once 'user.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Vérification mots de passe identiques
    if($_POST['password'] !== $_POST['password2']) {
        // Attention : ici on repart vers le dossier pages pour afficher l'erreur
        header("Location: ../pages/register.php?erreur=mdp_different");
        exit();
    }

    // 2. Vérification force (Studi : 10 car. + 1 Maj + 1 Chiffre)
    $pass = $_POST['password'];
    if (!preg_match('/[A-Z]/', $pass) || !preg_match('/[0-9]/', $pass) || strlen($pass) < 10) {
        header("Location: ../pages/register.php?erreur=mdp_faible");
        exit();
    }

    // ... la suite du code reste la même ...
    $user->nom = $_POST['nom'];
    $user->prenom = $_POST['prenom'];
    $user->email = $_POST['email'];
    $user->gsm = $_POST['gsm'];
    $user->adresse = $_POST['adresse'];
    $user->password = $pass; 

    if($user->register()) {
        header("Location: ../pages/login.php?success=compte_cree");
    } else {
        header("Location: ../pages/register.php?erreur=echec_bdd");
    }
}