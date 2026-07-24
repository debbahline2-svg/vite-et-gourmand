<?php
require_once '../includes/header.php';
require_once '../includes/database.php';

// 1. Vérification de sécurité
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'employe' && $_SESSION['role'] !== 'admin')) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

$id_commande = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = "";

// 2. Traitement de la maj
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $nouveau_statut = $_POST['statut'];
    
    // CORRECTION : Remplacement de u.email par u.identifiant
    $stmt_email = $db->prepare("SELECT u.identifiant FROM commandes c JOIN utilisateurs u ON c.user_id = u.id WHERE c.id = :id");
    $stmt_email->execute([':id' => $id_commande]);
    $client = $stmt_email->fetch(PDO::FETCH_ASSOC);

    $query = "UPDATE commandes SET statut = :statut WHERE id = :id";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([':statut' => $nouveau_statut, ':id' => $id_commande])) {
        $message = "<div class='alert alert-success'>Statut mis à jour !</div>";

        // Déclencheur email si "Terminée"
        if ($nouveau_statut === 'terminée' && $client) {
            $to = $client['identifiant']; // Utilisation de l'identifiant (email)
            $subject = "Votre commande est terminée !";
            $lien = "http://" . $_SERVER['HTTP_HOST'] . "/pages/avis.php?id_commande=" . $id_commande;
            $body = "Merci pour votre commande. Votre avis compte pour nous, donnez votre note ici : " . $lien;
            //  mail() ne fonctionnera qu'en ligne (sur un serveur SMTP)
            @mail($to, $subject, $body);
        }

        header("Refresh: 2; url=employe_dashboard.php");
    }
}

// 3. Récupération des infos et correction de la requête aussi pour plus de sécurité)
$query_cmd = "SELECT c.*, u.nom, u.prenom FROM commandes c 
              JOIN utilisateurs u ON c.user_id = u.id 
              WHERE c.id = :id";
$stmt_cmd = $db->prepare($query_cmd);
$stmt_cmd->execute([':id' => $id_commande]);
$commande = $stmt_cmd->fetch(PDO::FETCH_ASSOC);

if (!$commande) { die("<div class='container mt-5 alert alert-danger'>Commande introuvable.</div>"); }
?>

<div class="container mt-5" style="color: white; max-width: 600px;">
    <div style="background: #161513; border: 1px solid #C17F3A; border-radius: 15px; padding: 30px;">
        <h2 style="color: #C17F3A; font-family: 'Playfair Display', serif;" class="mb-4">Gérer la commande #<?= $commande['id'] ?></h2>
        
        <?= $message ?>

        <p><strong>Client :</strong> <?= htmlspecialchars($commande['prenom'] . " " . $commande['nom']) ?></p>
        <p><strong>Statut actuel :</strong> <span class="badge" style="background: #C17F3A; color: black;"><?= strtoupper($commande['statut']) ?></span></p>

        <form method="POST" class="mt-4">
            <label class="form-label text-muted small">CHANGER LE STATUT</label>
            <select name="statut" class="form-select bg-dark text-white border-secondary mb-4">
                <option value="en attente" <?= $commande['statut'] == 'en attente' ? 'selected' : '' ?>>En attente</option>
                <option value="accepté" <?= $commande['statut'] == 'accepté' ? 'selected' : '' ?>>Accepté</option>
                <option value="en préparation" <?= $commande['statut'] == 'en préparation' ? 'selected' : '' ?>>En préparation</option>
                <option value="en cours de livraison" <?= $commande['statut'] == 'en cours de livraison' ? 'selected' : '' ?>>En cours de livraison</option>
                <option value="livré" <?= $commande['statut'] == 'livré' ? 'selected' : '' ?>>Livré</option>
                <option value="terminée" <?= $commande['statut'] == 'terminée' ? 'selected' : '' ?>>Terminée</option>
            </select>

            <div class="d-flex gap-2">
                <button type="submit" name="update_status" class="btn fw-bold w-100" style="background: #C17F3A; color: black;">VALIDER LE CHANGEMENT</button>
                <a href="employe_dashboard.php" class="btn btn-outline-light w-100">RETOUR</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>