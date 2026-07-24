<?php
require_once '../includes/header.php';
require_once '../includes/database.php';

// Sécurité admin seulement
if ($_SESSION['role'] !== 'admin') { header('Location: ../index.php'); exit; }

$db = (new Database())->getConnection();

// Action activer/désactiver
if (isset($_GET['id']) && isset($_GET['action'])) {
    $new_status = ($_GET['action'] == 'activer') ? 1 : 0;
    $db->prepare("UPDATE utilisateurs SET is_active = ? WHERE id = ?")->execute([$new_status, $_GET['id']]);
    header('Location: admin_utilisateurs.php');
}

$employes = $db->query("SELECT * FROM utilisateurs WHERE role = 'employe'")->fetchAll();
?>

<div class="container mt-5" style="color:white;">
    <h2>Gestion des employés</h2>
    <table class="table table-dark">
        <?php foreach ($employes as $e): ?>
        <tr>
            <td><?= $e['identifiant'] ?></td>
            <td><?= $e['is_active'] ? 'Actif' : 'Bloqué' ?></td>
            <td>
                <a href="?id=<?= $e['id'] ?>&action=<?= $e['is_active'] ? 'desactiver' : 'activer' ?>" 
                   class="btn btn-<?= $e['is_active'] ? 'danger' : 'success' ?>">
                   <?= $e['is_active'] ? 'Désactiver' : 'Activer' ?>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="admin_dashboard.php">Retour Dashboard</a>
</div>