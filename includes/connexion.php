<?php
require_once 'Database.php';
require_once 'User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

if ($_POST) {
$user->email = htmlspecialchars(trim($_POST['email']));
$user->password = $_POST['password'];

$resultat = $user->login();

if ($resultat) {
// On demarre la session et on stocke les infos de l'utilisateur
session_start();
$_SESSION['user'] = $resultat;

// Redirection selon le role
if ($resultat['role'] === 'admin') {
header("Location: ../pages/espace-admin.php");
} elseif ($resultat['role'] === 'employe') {
header("Location: ../pages/espace-employe.php");
} else {
header("Location: ../pages/espace-utilisateur.php");
}
exit();
} else {
header("Location: ../pages/login.php?erreur=identifiants");
exit();
}
}
?>



