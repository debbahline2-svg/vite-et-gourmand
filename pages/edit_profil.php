<?php 
require_once '../includes/header.php'; 
require_once '../includes/db.php'; 
require_once '../includes/user.php';

if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php'); 
    exit; 
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user->id = $_SESSION['user_id'];
    $user->nom = $_POST['nom'];
    $user->prenom = $_POST['prenom'];
    $user->gsm = $_POST['gsm'];
    $user->adresse = $_POST['adresse'];

    if ($user->update()) {
        // Optionnel : mettre à jour le nom en session s'il a changé
        $_SESSION['nom'] = $user->nom;
        echo "<script>window.location.href='profil.php';</script>";
        exit;
    }
}

// On récupère les infos à jour pour pré-remplir le formulaire
$userInfo = $user->getOneById($_SESSION['user_id']);
?>

<style>
    body { background-color: #0c0b0a !important; color: white; }
    .edit-card { background: #161513; border: 1px solid #C17F3A; border-radius: 15px; padding: 40px; margin-top: 50px; }
    .form-control { background-color: #0c0b0a !important; border: 1px solid #333 !important; color: white !important; }
    .form-control:focus { border-color: #C17F3A !important; box-shadow: none; color: white; }
    .btn-gold { background: #C17F3A; color: black; font-weight: bold; border: none; padding: 12px; border-radius: 10px; transition: 0.3s; }
    .btn-gold:hover { background: white; color: black; }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="edit-card">
                <h2 class="text-center" style="color: #C17F3A; font-family: 'Playfair Display';">Modifier mon profil</h2>
                <form method="POST" class="mt-4">
                    <div class="mb-3">
                        <label class="small text-muted">Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($userInfo['prenom']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Nom</label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($userInfo['nom']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">GSM</label>
                        <input type="text" name="gsm" class="form-control" value="<?= htmlspecialchars($userInfo['gsm']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted">Adresse</label>
                        <textarea name="adresse" class="form-control" rows="3" required><?= htmlspecialchars($userInfo['adresse']) ?></textarea>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-gold w-100">ENREGISTRER</button>
                        <a href="profil.php" class="btn btn-outline-secondary w-100">ANNULER</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>