<?php
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../includes/user.php';

// Sécurité Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    // On remplit les infos du futur employé
    $user->prenom = $_POST['prenom'];
    $user->nom = $_POST['nom'];
    $user->email = $_POST['email'];
    $user->gsm = $_POST['gsm'];
    $user->adresse = "Adresse Entreprise"; // Valeur par défaut
    $user->password = $_POST['password']; // Sera haché dans User.php
    $user->role = 'employe'; // ON FORCE LE RÔLE EMPLOYÉ 

    if ($user->register()) {
        $message = "<div class='alert alert-success'>Compte employé créé ! Il peut maintenant se connecter.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Erreur : cet email est peut-être déjà utilisé.</div>";
    }
}
?>

<div class="container mt-5" style="max-width: 500px; color: white;">
    <div style="background: #161513; border: 1px solid #C17F3A; border-radius: 20px; padding: 40px;">
        <h2 class="text-center" style="color: #C17F3A; font-family: 'Playfair Display'; margin-bottom: 30px;">Recruter un Employé</h2>
        
        <?= $message ?>

        <form method="POST">
            <div class="mb-3">
                <label class="small text-muted">PRÉNOM</label>
                <input type="text" name="prenom" class="form-control bg-dark text-white border-secondary" required>
            </div>
            <div class="mb-3">
                <label class="small text-muted">NOM</label>
                <input type="text" name="nom" class="form-control bg-dark text-white border-secondary" required>
            </div>
            <div class="mb-3">
                <label class="small text-muted">EMAIL (IDENTIFIANT)</label>
                <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required>
            </div>
            <div class="mb-3">
                <label class="small text-muted">GSM</label>
                <input type="text" name="gsm" class="form-control bg-dark text-white border-secondary" required>
            </div>
            <div class="mb-4">
                <label class="small text-muted">MOT DE PASSE PROVISOIRE</label>
                <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
            </div>
            
            <button type="submit" class="btn btn-warning w-100 fw-bold py-2" style="background: #C17F3A; color: black; border: none;">CRÉER LE COMPTE</button>
            <a href="admin_dashboard.php" class="btn btn-link w-100 text-muted mt-2">Retour au Dashboard</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>