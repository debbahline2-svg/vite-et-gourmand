<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'])) {
    $db = (new Database())->getConnection();
    $id = $_POST['id_commande'];
    // récupère la raison pour la traçabilité
    $raison = htmlspecialchars($_POST['raison'] ?? 'Non spécifiée');

    //  met à jour le statut
    $stmt = $db->prepare("UPDATE commandes SET statut = 'Annulée' WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);

    // Redirection vers le profil avec un message de succès
    header('Location: profil.php?status=cancelled&msg=' . urlencode("Commande annulée. Raison : " . $raison));
    exit;
}
?>