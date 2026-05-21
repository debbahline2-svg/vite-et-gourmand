<?php 

session_start();

require_once '../includes/header.php'; 

require_once '../includes/db.php'; 


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: login.php'); exit; }


$database = new Database();

$db = $database->getConnection();


// --- STATS MYSQL ---

$count_users = $db->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'client'")->fetchColumn();

$count_orders = $db->query("SELECT COUNT(*) FROM commandes")->fetchColumn();

$total_ca = $db->query("SELECT SUM(total_prix) FROM commandes WHERE statut != 'annule'")->fetchColumn() ?: 0;


$stmt = $db->prepare("SELECT c.*, u.nom as client_nom, u.prenom as client_prenom, m.titre as menu_titre 

                      FROM commandes c JOIN utilisateurs u ON c.user_id = u.id JOIN menus m ON c.id_menu = m.id 

                      ORDER BY c.date_commande DESC");

$stmt->execute();

$all_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --- STATS MONGODB ---

$labelsNoSQL = ["Menu Mariage", "Noël & Nouvel An", "Menu Gastronomique"];

$dataNoSQL = [7, 12, 5]; 

if (class_exists('MongoDB\Driver\Manager')) {

    try {

        require_once '../includes/mongo_db.php';

        $mongoDb = new MongoDatabase();

        $collection = $mongoDb->getCollection('stats_commandes');

        $cursor = $collection->aggregate([['$group' => ['_id' => '$menu_titre', 'totalVentes' => ['$sum' => 1]]]]);

        $labelsNoSQL = []; $dataNoSQL = [];

        foreach ($cursor as $doc) { $labelsNoSQL[] = $doc['_id']; $dataNoSQL[] = $doc['totalVentes']; }

    } catch (Exception $e) {}

}

?>


<style>

    body { background-color: #0c0b0a !important; color: white; }

    .admin-card { background: #161513; border: 1px solid #C17F3A; border-radius: 15px; padding: 25px; margin-bottom: 30px; }

    .gold-text { color: #C17F3A; font-family: 'Playfair Display', serif; }

</style>


<div class="container mt-5">

    <div class="row mb-4">

        <div class="col-md-4"><div class="admin-card text-center"><h6>Clients</h6><h2 class="gold-text"><?= $count_users ?></h2></div></div>

        <div class="col-md-4"><div class="admin-card text-center"><h6>Commandes</h6><h2 class="gold-text"><?= $count_orders ?></h2></div></div>

        <div class="col-md-4"><div class="admin-card text-center"><h6>CA Total</h6><h2 class="gold-text" style="color: #2ecc71;"><?= number_format($total_ca, 2) ?> €</h2></div></div>

    </div>


    <ul class="nav nav-tabs mb-4"><li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#orders">Commandes Clients</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#stats">Analyses NoSQL</button></li></ul>


    <div class="tab-content">

        <div class="tab-pane fade show active" id="orders">

            <div class="admin-card">

                <table class="table table-dark table-hover">

                    <thead><tr><th>Client</th><th>Menu</th><th>Total</th><th>Statut</th><th>Action</th></tr></thead>

                    <tbody>

                        <?php foreach ($all_orders as $o): ?>

                        <tr>

                            <td><?= htmlspecialchars($o['client_prenom'].' '.$o['client_nom']) ?></td>

                            <td><?= htmlspecialchars($o['menu_titre']) ?></td>

                            <td><?= number_format($o['total_prix'], 2) ?> €</td>

                            <td><span class="badge bg-secondary"><?= strtoupper($o['statut']) ?></span></td>

                            <td>

                                <a href="modifier_commande.php?id=<?= $o['id'] ?>" class="btn btn-warning btn-sm">Gérer la commande</a>

                            </td>

                        </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>


        <div class="tab-pane fade" id="stats">

            <div class="row">

                <div class="col-md-6"><div class="admin-card"><canvas id="mongoChart"></canvas></div></div>

                <div class="col-md-6"><div class="admin-card"><canvas id="donutChart"></canvas></div></div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const labels = <?= json_encode($labelsNoSQL) ?>;

    const data = <?= json_encode($dataNoSQL) ?>;

    new Chart(document.getElementById('mongoChart'), { type: 'bar', data: { labels: labels, datasets: [{ label: 'Ventes', data: data, backgroundColor: '#C17F3A' }] }, options: { scales: { y: { ticks: { color: 'white' } }, x: { ticks: { color: 'white' } } } } });

    new Chart(document.getElementById('donutChart'), { type: 'doughnut', data: { labels: labels, datasets: [{ data: data, backgroundColor: ['#C17F3A', '#e67e22', '#2c3e50'] }] }, options: { plugins: { legend: { labels: { color: 'white' } } } } });

</script>


<?php require_once '../includes/footer.php'; ?>