<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Sécurité : Vérification du rôle
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'employe' && $_SESSION['role'] !== 'admin')) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Requête SQL  récupérer les commandes avec les infos 
$query = "SELECT c.*, u.nom, u.prenom, m.titre 
          FROM commandes c 
          JOIN utilisateurs u ON c.user_id = u.id 
          JOIN menus m ON c.id_menu = m.id
          ORDER BY c.date_commande DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$allCommandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5" style="color: white; padding-bottom: 100px;">
    <div class="d-flex justify-content-between align-items-center">
        <h2 style="color: #C17F3A; font-family: 'Playfair Display', serif;">Espace Gestion Employé</h2>
        <span class="badge bg-outline-warning border border-warning text-warning p-2">Rôle : <?= strtoupper($_SESSION['role']) ?></span>
    </div>

    <div class="mt-5" style="background: #161513; border: 1px solid #C17F3A; border-radius: 15px; padding: 30px;">
        <h4 class="mb-4">Liste de toutes les commandes</h4>
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr style="color: #C17F3A; border-bottom: 2px solid #C17F3A;">
                        <th>ID</th>
                        <th>Client</th>
                        <th>Menu</th>
                        <th>Distance</th>
                        <th>Total</th>
                        <th>Date Événement</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allCommandes as $com): ?>
                    <tr>
                        <td><strong>#<?= $com['id'] ?></strong></td>
                        <td><?= htmlspecialchars($com['prenom'] . ' ' . $com['nom']) ?></td>
                        <td><?= htmlspecialchars($com['titre']) ?></td>
                        <td><?= htmlspecialchars($com['distance_km']) ?> km</td>
                        <td><?= number_format($com['total_prix'], 2, ',', ' ') ?> €</td>
                        <td><?= date('d/m/Y', strtotime($com['date_evenement'])) ?></td>
                        <td>
                            <span class="badge" style="background: #C17F3A; color: black;">
                                <?= strtoupper($com['statut']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="modifier_commande.php?id=<?= $com['id'] ?>" class="btn btn-sm btn-warning">Gérer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>