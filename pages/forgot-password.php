<?php
require_once '../includes/header.php';
require_once '../includes/database.php';

$message = "";
$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        $erreur = "Veuillez saisir une adresse email valide.";
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        // CORRECTION : On cherche dans la colonne "identifiant"
        $stmt = $db->prepare("SELECT id FROM utilisateurs WHERE identifiant = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $lien_reinitialisation = "http://localhost/vite-et-gourmand/pages/reset-password.php?email=" . urlencode($email) . "&token=" . $token;
            
            $to = $email;
            $subject = "[Vite & Gourmand] Réinitialisation de votre mot de passe";
            $body = "Bonjour,\n\nVous avez demandé la réinitialisation de votre mot de passe.\n";
            $body .= "Cliquez sur le lien ci-dessous pour configurer un nouveau mot de passe :\n";
            $body .= $lien_reinitialisation;
            $headers = "From: no-reply@viteetgourmand.fr";
            
            @mail($to, $subject, $body, $headers);
            
            $message = "Si cette adresse existe, un e-mail de réinitialisation vient de lui être envoyé avec un lien sécurisé.";
        } else {
            $message = "Si cette adresse existe, un e-mail de réinitialisation vient de lui être envoyé avec un lien sécurisé.";
        }
    }
}
?>

<style>
    body { background-color: #0c0b0a !important; color: white; font-family: 'Roboto', sans-serif; }
    .forgot-card { background: #161513; border: 1px solid #C17F3A; border-radius: 20px; padding: 40px; width: 100%; max-width: 500px; margin: 80px auto; }
    .form-control { background-color: #1a1a1a !important; border: 1px solid #333 !important; color: white !important; }
    .btn-gold { background: #C17F3A; color: black; font-weight: 700; border: none; padding: 12px; border-radius: 10px; width: 100%; margin-top: 15px; transition: 0.3s; }
    .btn-gold:hover { background: #e6a75a; cursor: pointer; }
    label { color: #aaa; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 5px; display: block; }
</style>

<div class="container">
    <div class="forgot-card shadow-lg">
        <h2 class="text-center mb-4" style="color: #C17F3A; font-family: 'Playfair Display', serif;">Oubli de Mot de passe</h2>
        
        <?php if($message): ?>
            <div class="alert alert-success text-center border-0 small mb-4" style="background: rgba(193, 127, 58, 0.1); color: #C17F3A;">
                <?= $message ?>
            </div>
            <div class="text-center">
                <a href="login.php" class="btn btn-sm btn-outline-secondary text-uppercase mt-2">Retour à la connexion</a>
            </div>
        <?php else: ?>

            <?php if($erreur): ?>
                <div class="alert alert-danger text-center border-0 small mb-3" style="background: rgba(255,0,0,0.1); color: #ff6b6b;"><?= $erreur ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Votre adresse Email de connexion</label>
                    <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" required>
                </div>
                <button type="submit" class="btn btn-gold text-uppercase">Recevoir le lien</button>
            </form>
            
            <div class="text-center mt-4">
                <a href="login.php" class="small text-white-50 text-decoration-none">← Retourner se connecter</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>