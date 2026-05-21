<?php
require_once '../includes/db.php';
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'], $_POST['note'])) {
    $stmt = $db->prepare("UPDATE commandes SET note = ?, commentaire = ? WHERE id = ?");
    $stmt->execute([$_POST['note'], $_POST['commentaire'], $_POST['id_commande']]);
    
    header('Location: mes_commandes.php?success=1');
    exit;
}
?>