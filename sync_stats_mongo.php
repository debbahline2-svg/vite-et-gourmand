<?php
require_once 'includes/database.php';
require_once 'includes/mongo_db.php';

$database = new Database();
$db = $database->getConnection();

$stmt = $db->query("SELECT m.titre as menu_titre, COUNT(*) as total 
                     FROM commandes c 
                     JOIN menus m ON c.id_menu = m.id 
                     WHERE c.statut != 'annulee'
                     GROUP BY m.titre");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mongoDb = new MongoDatabase();
$collection = $mongoDb->getCollection('stats_commandes');
$collection->deleteMany([]);

foreach ($rows as $row) {
    for ($i = 0; $i < $row['total']; $i++) {
        $collection->insertOne(['menu_titre' => $row['menu_titre']]);
    }
}

echo "OK : " . count($rows) . " menus synchronisés."; 