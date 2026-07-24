<?php
require_once '../../includes/database.php';
require_once '../../includes/services/AvisService.php';
header('Content-Type: application/json');

$db = (new Database())->getConnection();
$service = new AvisService($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id_commande'])) {
    $result = $service->ajouterAvis(
        $_GET['id_commande'],
        $_POST['note'],
        $_POST['com']
    );
    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}