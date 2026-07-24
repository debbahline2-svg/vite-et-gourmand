<?php
require_once '../includes/header.php';
?>

<div id="avisMessage" class="container mt-3" style="display:none;"></div>

<form id="avisForm" method="POST" class="container mt-5" style="color:white;" data-id-commande="<?= isset($_GET['id_commande']) ? htmlspecialchars($_GET['id_commande']) : '' ?>">
    <label>Note (1-5)</label>
    <input type="number" name="note" min="1" max="5" class="form-control" required>
    <label>Commentaire</label>
    <textarea name="com" class="form-control" required></textarea>
    <button type="submit" class="btn btn-warning mt-3">Envoyer mon avis</button>
</form>

<script src="../js/avis.js"></script>

<?php require_once '../includes/footer.php'; ?>