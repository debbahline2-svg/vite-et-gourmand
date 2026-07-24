<?php
require_once '../includes/database.php';

$budget = isset($_GET['budget']) ? $_GET['budget'] : 'all';
$regime = isset($_GET['regime']) ? $_GET['regime'] : 'all';
$theme = isset($_GET['theme']) ? $_GET['theme'] : 'all';

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

foreach ($menus as $m) {
    echo '
    <div class="col-md-4">
        <div class="menu-card">
            <div class="badge-menu">'.$m['theme'].'</div>
            <h2>'.$m['titre'].'</h2>
            <p class="text-muted small">'.$m['description'].'</p>
            <div class="menu-price">'.number_format($m['prix'], 0).'€ <small>/ pers</small></div>
            <div class="info-min">Minimum : '.$m['nb_pers_min'].' personnes</div>
            <br>
            <a href="detail-menu.php?id='.$m['id'].'" class="btn-link">DÉCOUVRIR LA CARTE</a>
        </div>
    </div>';
}
?>