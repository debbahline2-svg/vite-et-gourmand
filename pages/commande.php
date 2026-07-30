<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/user.php';
 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
 
require_once '../includes/header.php';
require_once '../includes/services/CommandeService.php';
 
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$userInfo = $user->getOneById($_SESSION['user_id']);
 
$id_du_menu = isset($_GET['id']) ? intval($_GET['id']) : 1;
 
require_once '../includes/repositories/MenuRepository.php';
 
$menuRepository = new MenuRepository($db);
$menuInfo = $menuRepository->findById($id_du_menu);
 
if (!$menuInfo) {
    die("<div class='container mt-5 alert alert-danger text-center'>Erreur : Ce menu n'existe pas.</div>");
}
 
$prixUnitaire = isset($menuInfo['prix']) ? floatval($menuInfo['prix']) : ($menuInfo['prix_base'] ?? 75.00);
$nbMinPersonnes = isset($menuInfo['nb_pers_min']) ? intval($menuInfo['nb_pers_min']) : ($menuInfo['nb_personne_min'] ?? 10);
 
$error = null;
$success = false;
$message = "";
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nb_personnes = intval($_POST['nb_personnes']);
    $ville = trim($_POST['ville'] ?? '');
    $distance = floatval($_POST['distance'] ?? 0);
    $date_evenement = $_POST['date_prestation'];
 
    $commandeService = new CommandeService($db);
    $total_final_ttc = $commandeService->calculerTotal($prixUnitaire, $nb_personnes, $nbMinPersonnes, $ville, $distance);
 
    $result = $commandeService->creerCommande(
        $_SESSION['user_id'],
        $id_du_menu,
        $date_evenement,
        $nb_personnes,
        $total_final_ttc,
        $distance
    );
 
    $success = $result['success'];
    $message = $result['message'];
 
    if ($success) {
        require_once '../includes/Mailer.php';
        Mailer::envoyer(
            $userInfo['identifiant'],
            "Confirmation de votre commande - Vite & Gourmand",
            "<p>Bonjour " . htmlspecialchars($userInfo['prenom']) . ",</p>
             <p>Votre commande pour le menu <strong>" . htmlspecialchars($menuInfo['titre']) . "</strong> a bien été enregistrée.</p>
             <p>Montant total : " . number_format($total_final_ttc, 2, ',', ' ') . " €</p>
             <p>Merci de votre confiance !</p>"
        );
    }
 
    if (!$success) {
        $error = $result['message'];
    }
}
?>
 
<style>
    body { background-color: #0c0b0a !important; color: white; font-family: 'Poppins', sans-serif; }
    .gold-text { color: #C17F3A; font-family: 'Playfair Display', serif; }
    .form-control { background: #1a1a1a !important; border: 1px solid #333 !important; color: white !important; padding: 10px; }
    .form-control:focus { border-color: #C17F3A !important; box-shadow: none !important; }
</style>
 
<div class="container mt-5" style="max-width: 700px;">
    <div style="background: #12100E; border: 1px solid #C17F3A; border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <h2 class="text-center gold-text mb-4">Réserver : <?= htmlspecialchars($menuInfo['titre']) ?></h2>
 
        <?php if ($success): ?>
            <div class="alert alert-success text-center"><?= $message ?> <br><a href="menus.php" class="btn btn-sm btn-light mt-2">Retour aux menus</a></div>
        <?php else: ?>
            <?php if ($error): ?> <div class="alert alert-danger"><?= $error ?></div> <?php endif; ?>
 
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Ville de livraison</label>
                        <input type="text" name="ville" id="villeInput" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Distance (km)</label>
                        <input type="number" name="distance" id="distInput" class="form-control" value="0" step="0.1" required>
                    </div>
                </div>
 
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Date de réception</label>
                        <input type="date" name="date_prestation" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Convives (Min: <?= $nbMinPersonnes ?>)</label>
                        <input type="number" name="nb_personnes" id="nbPersonnes" class="form-control" min="<?= $nbMinPersonnes ?>" value="<?= $nbMinPersonnes ?>" required>
                    </div>
                </div>
 
                <div class="p-3 mb-4 text-center" style="background: #0c0b0a; border: 1px dashed #C17F3A;">
                    <div class="fw-bold fs-4" style="color: #C17F3A;">Total Estimé : <span id="totalDynamique">0,00 €</span></div>
                    <div id="reducTxt" class="text-success small fw-bold mt-1" style="display: none;">Remise de 10% appliquée !</div>
                </div>
 
                <button type="submit" class="btn w-100 py-3 text-uppercase" style="background: #C17F3A; color: black; font-weight: bold; border-radius: 8px;">Confirmer ma réservation</button>
            </form>
        <?php endif; ?>
    </div>
</div>
 
<script>
document.addEventListener("DOMContentLoaded", function() {
    const prixUnitaire = <?= $prixUnitaire ?>;
    const minConvives = <?= $nbMinPersonnes ?>;
    const inputNb = document.getElementById('nbPersonnes');
    const inputVille = document.getElementById('villeInput');
    const inputDist = document.getElementById('distInput');
    const totalDynamique = document.getElementById('totalDynamique');
    const reducTxt = document.getElementById('reducTxt');
 
    function rafraichirPrix() {
        let nb = parseInt(inputNb.value) || minConvives;
        let dist = parseFloat(inputDist.value) || 0;
        let total = prixUnitaire * nb;
 
        if (nb >= (minConvives + 5)) {
            total *= 0.90;
            reducTxt.style.display = "block";
        } else {
            reducTxt.style.display = "none";
        }
 
        if (inputVille.value.toLowerCase().trim() === 'bordeaux') {
            total += 5.00;
        } else if (inputVille.value.trim() !== '') {
            total += 5.00 + (dist * 0.59);
        }
 
        totalDynamique.innerText = total.toFixed(2).replace('.', ',') + " €";
    }
 
    inputNb.addEventListener('input', rafraichirPrix);
    inputVille.addEventListener('input', rafraichirPrix);
    inputDist.addEventListener('input', rafraichirPrix);
    rafraichirPrix();
});
</script>
 
<?php require_once '../includes/footer.php'; ?>
