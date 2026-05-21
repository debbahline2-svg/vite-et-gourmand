<?php 
require_once '../includes/header.php'; 
require_once '../includes/db.php'; 
require_once '../includes/user.php';

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database(); 
    $db = $database->getConnection();
    $user = new User($db);

    $user->email = $_POST['email'];
    $user->password = $_POST['password'];

    // Dans ta classe User, assure-toi que la méthode login() effectue bien la requête :
    // SELECT * FROM utilisateurs WHERE email = :email AND is_active = 1
    $userData = $user->login();

    if ($userData) {
        // Vérification supplémentaire de sécurité (au cas où la classe User ne le ferait pas)
        if (isset($userData['is_active']) && $userData['is_active'] == 0) {
            $erreur = "Votre compte a été désactivé. Veuillez contacter l'administrateur.";
        } else {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['nom'] = $userData['nom']; 
            $_SESSION['role'] = $userData['role'];
            
            // Redirection selon le rôle
            if ($userData['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } elseif ($userData['role'] === 'employe') {
                header('Location: employe_dashboard.php');
            } else {
                header('Location: ../index.php');
            }
            exit;
        }
    } else {
        $erreur = "Identifiant ou mot de passe incorrect ou compte désactivé.";
    }
}
?>

<div class="login-container" style="background-color: #0c0b0a; min-height: 85vh; display: flex; align-items: center; justify-content: center;">
    <div class="login-card" style="background: #161513; border: 1px solid #C17F3A; border-radius: 20px; padding: 50px; width: 100%; max-width: 450px;">
        <h2 class="text-center" style="color: #C17F3A; font-family: 'Playfair Display', serif;">Connexion</h2>
        
        <?php if($erreur): ?>
            <div class="alert alert-danger text-center" style="background: rgba(255,0,0,0.1); color: #ff6b6b; border: none; margin-top: 15px;">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <form method="POST" style="margin-top: 30px;">
            <div class="mb-3">
                <label style="color: #aaa; font-size: 0.8rem; text-transform: uppercase;">Identifiant (Email)</label>
                <input type="text" name="email" class="form-control" style="background: #1a1a1a; border: 1px solid #333; color: white;" required>
            </div>
            
            <div class="mb-2">
                <label style="color: #aaa; font-size: 0.8rem; text-transform: uppercase;">Mot de passe</label>
                <input type="password" name="password" class="form-control" style="background: #1a1a1a; border: 1px solid #333; color: white;" required>
            </div>

            <div class="mb-4 text-end">
                <a href="forgot-password.php" style="color: #C17F3A; font-size: 0.8rem; text-decoration: none; font-style: italic;">
                    Mot de passe oublié ?
                </a>
            </div>

            <button type="submit" class="btn w-100" style="background: #C17F3A; color: black; font-weight: bold; padding: 12px; border-radius: 10px; text-transform: uppercase; transition: 0.3s;">SE CONNECTER</button>
        </form>
        
        <div class="text-center mt-4">
            <span style="color: #aaa; font-size: 0.85rem;">Nouveau client ? </span>
            <a href="register.php" style="color: #C17F3A; font-size: 0.85rem; text-decoration: underline;">Créer un compte</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>