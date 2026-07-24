<?php 
require_once '../includes/header.php'; 
require_once '../includes/database.php'; 

// Sécurité : Seule Julie/José (admin) peut entrer mais faudra utiliser Klenkle
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Récupération de tous les menus
$query = "SELECT * FROM menus ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family:'Playfair Display'; color:var(--gold);">Gestion de la Carte</h2>
        <a href="ajouter_menu.php" class="btn btn-success">+ Ajouter un menu</a>
    </div>

    <?php if (isset($_GET['msg'])) : ?>
        <div class="alert alert-info"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-dark table-hover border-secondary">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Prix/Pers</th>
                    <th>Min Pers.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menus as $m): ?>
                <tr class="align-middle">
                    <td><img src="../assets/images/<?= $m['image'] ?>" width="60" class="rounded"></td>
                    <td><strong><?= htmlspecialchars($m['titre']) ?></strong></td>
                    <td><?= number_format($m['prix'], 2) ?> €</td>
                    <td><?= $m['nb_pers_min'] ?></td>
                    <td>
                        <a href="modifier_menu.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-warning">Modifier</a>
                        <a href="supprimer_menu_action.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce menu ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>