<?php 
require_once '../includes/header.php'; 
require_once '../includes/database.php'; 
require_once '../includes/user.php';

$message = "";
$erreur = "";

$prenom = $_POST['prenom'] ?? "";
$nom = $_POST['nom'] ?? "";
$email = $_POST['email'] ?? "";
$gsm = $_POST['gsm'] ?? "";
$adresse = $_POST['adresse'] ?? "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // VÉRIF ÉTAPE PAR ÉTAPE
    if ($password !== $confirm_password) {
        $erreur = "Les mots de passe ne sont pas identiques.";
    } elseif (strlen($password) < 10) {
        $erreur = "Le mot de passe est trop court (10 caractères minimum).";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $erreur = "Il manque une MAJUSCULE.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $erreur = "Il manque une minuscule.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $erreur = "Il manque un chiffre.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $erreur = "Il manque un caractère spécial (ex: @, !, ?, /).";
    } elseif (!isset($_POST['rgpd_consent'])) { 
        // Sécurité côté serveur
        $erreur = "Vous devez accepter la politique de confidentialité.";
    } else {
        $user->nom = $_POST['nom'];
        $user->prenom = $_POST['prenom'];
        $user->email = $_POST['email'];
        $user->gsm = $_POST['gsm'];
        $user->adresse = $_POST['adresse'];
        $user->password = $password;

        if ($user->register()) {
            $message = "Compte créé ! Bienvenue chez Julie & José.";
            $prenom = $nom = $email = $gsm = $adresse = "";
        } else {
            $erreur = "Cet email est déjà utilisé.";
        }
    }
}
?>

<style>
    body { background-color: #0c0b0a !important; color: white; font-family: 'Roboto', sans-serif; }
    .register-card { background: #161513; border: 1px solid #C17F3A; border-radius: 20px; padding: 40px; width: 100%; max-width: 600px; margin: 50px auto; }
    .form-control { background-color: #1a1a1a !important; border: 1px solid #333 !important; color: white !important; }
    .btn-gold { background: #C17F3A; color: black; font-weight: 700; border: none; padding: 12px; border-radius: 10px; width: 100%; margin-top: 20px; transition: 0.3s; }
    .btn-gold:hover { background: #e6a75a; cursor: pointer; }
    label { color: #aaa; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 5px; display: block; }
    .pass-container { position: relative; }
    .eye-btn { position: absolute; right: 10px; top: 35px; cursor: pointer; filter: grayscale(1); }
    
    /* Style personnalisé pour la checkbox RGPD */
    .form-check-input:checked {
        background-color: #C17F3A !important;
        border-color: #C17F3A !important;
    }
</style>

<div class="container">
    <div class="register-card">
        <h2 class="text-center mb-4" style="color: #C17F3A; font-family: 'Playfair Display', serif;">Inscription</h2>
        
        <?php if($message): ?>
            <div class="alert alert-success text-center border-0" style="background: rgba(0,255,0,0.1); color: #72f572;"><?= $message ?></div>
        <?php endif; ?>

        <?php if($erreur): ?>
            <div class="alert alert-danger text-center border-0" style="background: rgba(255,0,0,0.1); color: #ff6b6b;"><?= $erreur ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($prenom) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <div class="mb-3">
                <label>Téléphone GSM</label>
                <input type="text" name="gsm" class="form-control" value="<?= htmlspecialchars($gsm) ?>" required>
            </div>

            <div class="mb-3">
                <label>Adresse Postale</label>
                <textarea name="adresse" class="form-control" rows="2" required><?= htmlspecialchars($adresse) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3 pass-container">
                    <label>Mot de passe</label>
                    <input type="password" name="password" id="p1" class="form-control" required>
                    <span class="eye-btn" onclick="toggle('p1')">👁️</span>
                </div>
                <div class="col-md-6 mb-3 pass-container">
                    <label>Confirmation</label>
                    <input type="password" name="confirm_password" id="p2" class="form-control" required>
                    <span class="eye-btn" onclick="toggle('p2')">👁️</span>
                </div>
            </div>

            <div class="mb-3 form-check text-start mt-3">
                <input type="checkbox" name="rgpd_consent" class="form-check-input" id="rgpd" required>
                <label class="form-check-label small text-white-50" for="rgpd" style="text-transform: none; display: inline; font-size: 0.85rem;">
                    J'accepte que mes données soient collectées et conservées conformément aux 
                    <a href="mentions-legales.php" target="_blank" style="color: #C17F3A; text-decoration: underline;">Mentions Légales</a> 
                    et à la politique RGPD de Vite & Gourmand. *
                </label>
            </div>

            <button type="submit" class="btn btn-gold text-uppercase">Créer mon compte</button>
        </form>
    </div>
</div>

<script>
function toggle(id) {
    const el = document.getElementById(id);
    el.type = el.type === "password" ? "text" : "password";
}
</script>

<?php require_once '../includes/footer.php'; ?>