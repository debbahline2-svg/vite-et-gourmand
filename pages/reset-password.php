<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

$message = "";
$erreur = "";
$email_user = $_GET['email'] ?? '';
$token_user = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_user = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $erreur = "Les mots de passe ne sont pas identiques.";
    } elseif (strlen($password) < 10) {
        $erreur = "Le mot de passe doit faire 10 caractères minimum.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $erreur = "Il manque une MAJUSCULE.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $erreur = "Il manque une minuscule.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $erreur = "Il manque un chiffre.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $erreur = "Il manque un caractère spécial (ex: @, !, ?, /).";
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        // Utilisation de PASSWORD_BCRYPT pour être raccord avec la méthode register()
        $new_hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Ciblage des colonnes mdp et idtf
        $stmt = $db->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE identifiant = ?");
        if ($stmt->execute([$new_hash, $email_user])) {
            $message = "Votre mot de passe a bien été réinitialisé ! Vous pouvez maintenant vous connecter.";
        } else {
            $erreur = "Une erreur technique est survenue.";
        }
    }
}
?>

<style>
    body { background-color: #0c0b0a !important; color: white; font-family: 'Roboto', sans-serif; }
    .reset-card { background: #161513; border: 1px solid #C17F3A; border-radius: 20px; padding: 40px; width: 100%; max-width: 500px; margin: 80px auto; }
    .form-control { background-color: #1a1a1a !important; border: 1px solid #333 !important; color: white !important; }
    .btn-gold { background: #C17F3A; color: black; font-weight: 700; border: none; padding: 12px; border-radius: 10px; width: 100%; margin-top: 15px; transition: 0.3s; }
    .btn-gold:hover { background: #e6a75a; cursor: pointer; }
    label { color: #aaa; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 5px; display: block; }
</style>

<div class="container">
    <div class="reset-card shadow-lg">
        <h2 class="text-center mb-4" style="color: #C17F3A; font-family: 'Playfair Display', serif;">Nouveau Mot de passe</h2>
        
        <?php if($message): ?>
            <div class="alert alert-success text-center border-0 small mb-4" style="background: rgba(0,255,0,0.1); color: #72f572;">
                <?= $message ?>
            </div>
            <a href="login.php" class="btn btn-gold text-uppercase text-center d-block text-decoration-none">Se connecter</a>
        <?php else: ?>

            <?php if($erreur): ?>
                <div class="alert alert-danger text-center border-0 small mb-3" style="background: rgba(255,0,0,0.1); color: #ff6b6b;"><?= $erreur ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email_user) ?>">

                <div class="mb-3">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control" required placeholder="10 caractères min, MAJ, min, chiffre, spécial">
                </div>
                
                <div class="mb-3">
                    <label>Confirmez le mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control" required placeholder="Répétez le mot de passe">
                </div>

                <button type="submit" class="btn btn-gold text-uppercase">Mettre à jour mon mot de passe</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>