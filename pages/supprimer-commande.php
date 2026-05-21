<?php
session_start();
require_once '../includes/db.php';

// Sécurité : Vérifie si l'admin est connecté
if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Initialisation de la connexion (indispensable pour utiliser $db)
    $database = new Database();
    $db = $database->getConnection();
    
    // Correction ici : on utilise $db au lieu de $pdo
    $req = $db->prepare("DELETE FROM commandes WHERE id = ?");
    $req->execute([$id]);
}

// On revient sur l'admin après la suppression
header('Location: admin.php?msg=deleted');
exit();