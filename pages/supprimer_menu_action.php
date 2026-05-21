<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

$id = intval($_GET['id']);
$query = "DELETE FROM menus WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$id]);

header('Location: admin_menus.php?msg=Menu supprimé');
exit;