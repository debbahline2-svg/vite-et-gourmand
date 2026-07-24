<?php
require_once '../includes/database.php';
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_commande'], $_POST['nouveau_statut'])) {
    $stmt = $db->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
    $stmt->execute([$_POST['nouveau_statut'], $_POST['id_commande']]);
    
    header('Location: admin_dashboard.php');
    exit;
}
?>