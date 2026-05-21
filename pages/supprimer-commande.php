<?php
session_start();
require_once '../includes/db.php';

// Sécurité admin est connecté
if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Initialisation de la connexion
    $database = new Database();
    $db = $database->getConnection();
    
    // Correction $db au lieu de $pdo
    $req = $db->prepare("DELETE FROM commandes WHERE id = ?");
    $req->execute([$id]);
}

// On revient a admin après la suppression
header('Location: admin.php?msg=deleted');
exit();