<?php 
require_once '../includes/header.php'; 
require_once '../includes/database.php'; 

// Sécurité : Seul l'admin peut voir cette page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// On récupère TOUTES les commandes pour Julie et José
$query = "SELECT c.*, u.nom as client_nom, u.prenom as client_prenom, m.titre as menu_titre 
          FROM commandes c 
          JOIN utilisateurs u ON c.user_id = u.id 
          JOIN menus m ON c.id_menu = m.id 
          ORDER BY c.date_commande DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$all_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 style="font-family: 'Playfair Display'; color: #C17F3A;">Gestion des Commandes (Julie & José)</h2>
    
    <div class="table-responsive mt-4">
        <table class="table table-dark table-hover border-secondary">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Menu</th>
                    <th>Date Event</th>
                    <th>Convives</th>
                    <th>Total TTC</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['client_prenom'] . ' ' . $order['client_nom']) ?></td>
                        <td><?= htmlspecialchars($order['menu_titre']) ?></td>
                        <td><?= date('d/m/Y', strtotime($order['date_evenement'])) ?></td>
                        <td><?= $order['nb_personnes'] ?></td>
                        <td style="color: #C17F3A; font-weight: bold;"><?= number_format($order['total_prix'], 2, ',', ' ') ?> €</td>
                        <td><span class="badge bg-warning text-dark"><?= $order['statut'] ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>