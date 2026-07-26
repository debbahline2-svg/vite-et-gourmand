<?php
require_once '../includes/database.php';

$database = new Database();
$pdo = $database->getConnection();

$budget = isset($_GET['budget']) ? $_GET['budget'] : 'all';
$regime = isset($_GET['regime']) ? $_GET['regime'] : 'all';
$theme  = isset($_GET['theme'])  ? $_GET['theme']  : 'all';

$sql = "SELECT * FROM menus WHERE 1=1";
$params = [];

if ($budget != 'all') {
    $sql .= " AND prix <= ?";
    $params[] = $budget;
}
if ($regime != 'all') {
    $sql .= " AND regime = ?";
    $params[] = $regime;
}
if ($theme != 'all') {
    $sql .= " AND theme = ?";
    $params[] = $theme;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ... reste du fichier inchangé (foreach $menus...)