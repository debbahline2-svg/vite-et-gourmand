<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

if (isset($_GET['id_commande'])) {
    $db = (new Database())->getConnection();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sql = "INSERT INTO avis (id_commande, note, commentaire) VALUES (?, ?, ?)";
        $db->prepare($sql)->execute([$_GET['id_commande'], $_POST['note'], $_POST['com']]);
        echo "<div class='alert alert-success'>Merci pour votre avis !</div>";
    }
}
?>
<form method="POST" class="container mt-5" style="color:white;">
    <label>Note (1-5)</label>
    <input type="number" name="note" min="1" max="5" class="form-control" required>
    <label>Commentaire</label>
    <textarea name="com" class="form-control" required></textarea>
    <button type="submit" class="btn btn-warning mt-3">Envoyer mon avis</button>
</form>