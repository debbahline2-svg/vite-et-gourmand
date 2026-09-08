<?php
require_once '../includes/header.php';
require_once '../includes/database.php';

// Sécurité admin seulement
if ($_SESSION['role'] !== 'admin') { header('Location: ../index.php'); exit; }

$db = (new Database())->getConnection();

// Action activer/désactiver — en POST uniquement, jamais en GET
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    $id_cible = intval($_POST['id']);
    $action = $_POST['action'];

    // On vérifie le rôle réel de la cible en base, jamais confiance
    // en ce que l'interface a affiché — protection contre la manipulation d'URL/formulaire
    $stmt_check = $db->prepare("SELECT role FROM utilisateurs WHERE id = ?");
    $stmt_check->execute([$id_cible]);
    $role_cible = $stmt_check->fetchColumn();

    if ($role_cible !== 'employe') {
        // On refuse toute action sur un compte qui n'est pas un employé
        // (protège les admins ET les clients d'une désactivation via manipulation)
        header('Location: admin_utilisateurs.php?erreur=action_non_autorisee');
        exit;
    }

    $new_status = ($action === 'activer') ? 1 : 0;
    $stmt = $db->prepare("UPDATE utilisateurs SET is_active = ? WHERE id = ?");
    $stmt->execute([$new_status, $id_cible]);

    header('Location: admin_utilisateurs.php');
    exit;
}

$employes = $db->query("SELECT * FROM utilisateurs WHERE role = 'employe'")->fetchAll();
?>

<div class="container mt-5" style="color:white;">
    <h2>Gestion des employés</h2>

    <?php if (isset($_GET['erreur']) && $_GET['erreur'] === 'action_non_autorisee'): ?>
        <div class="alert alert-danger">Action non autorisée sur ce compte.</div>
    <?php endif; ?>

    <table class="table table-dark">
        <?php foreach ($employes as $e): ?>
            <tr>
                <td><?= $e['identifiant'] ?></td>
                <td><?= $e['is_active'] ? 'Actif' : 'Bloqué' ?></td>
                <td>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Confirmer cette action ?');">
                        <input type="hidden" name="id" value="<?= $e['id'] ?>">
                        <input type="hidden" name="action" value="<?= $e['is_active'] ? 'desactiver' : 'activer' ?>">
                        <button type="submit" class="btn btn-<?= $e['is_active'] ? 'danger' : 'success' ?>">
                            <?= $e['is_active'] ? 'Désactiver' : 'Activer' ?>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="admin_dashboard.php">Retour Dashboard</a>
</div>