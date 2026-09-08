<?php
session_start();
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/services/AvisService.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté.']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $service = new AvisService($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $result = $service->supprimerAvis(
            $_POST['id'],
            $_SESSION['user_id'],
            $_SESSION['role']
        );
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Une erreur serveur est survenue.']);
}