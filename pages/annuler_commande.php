<?php
require_once '../includes/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'])) {
    $db = (new Database())->getConnection();
    $id = $_POST['id_commande'];
    $raison = htmlspecialchars($_POST['raison'] ?? 'Non spécifiée');

    // Vérifie que la commande appartient à l'utilisateur ET qu'elle est encore "en attente"
    $stmtCheck = $db->prepare("SELECT statut FROM commandes WHERE id = ? AND user_id = ?");
    $stmtCheck->execute([$id, $_SESSION['user_id']]);
    $commande = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$commande) {
        header('Location: profil.php?status=cancelled&msg=' . urlencode('Commande introuvable.'));
        exit;
    }

    $statutActuel = strtolower(trim($commande['statut']));
    if ($statutActuel !== 'en attente') {
        header('Location: profil.php?status=cancelled&msg=' . urlencode('Cette commande ne peut plus être annulée.'));
        exit;
    }

    // met à jour le statut
    $stmt = $db->prepare("UPDATE commandes SET statut = 'Annulée' WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);

    header('Location: profil.php?status=cancelled&msg=' . urlencode("Commande annulée. Raison : " . $raison));
    exit;
}
?>