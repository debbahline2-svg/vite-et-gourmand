<?php
session_start();
require_once '../includes/database.php';

// Inclusion de PHPMailer (installé via Composer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../vendor/autoload.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = htmlspecialchars($_POST['nom']);
    $email    = htmlspecialchars($_POST['email']);
    $convives = intval($_POST['convives']);
    $ville    = htmlspecialchars($_POST['ville']); // On récupère la ville
    $distance = floatval($_POST['distance'] ?? 0); // La distance en km envoyée par le formulaire

    // CALCUL DU PRIX DE LIVRAISON : 5,59€/km si c'est hors Bordeaux
    $prix_livraison = 0;
    if (strtolower($ville) !== 'bordeaux') {
        $prix_livraison = $distance * 5.59;
    }

    // CALCUL DU PRIX TOTAL (Exemple : 20€ par convive + livraison)
    $prix_menu = $convives * 20;
    $total_final = $prix_menu + $prix_livraison;

    try {
        // 1. Insertion de la commande en BDD (MySQL)
        $sql = "INSERT INTO commandes (nom_client, email_client, convives, ville, distance, total_price, date_commande, statut) 
                VALUES (:nom, :email, :convives, :ville, :distance, :total, NOW(), 'en attente')";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'nom'      => $nom,
            'email'    => $email,
            'convives' => $convives,
            'ville'    => $ville,
            'distance' => $distance,
            'total'    => $total_final
        ]);

        // 2. Envoi de l'e-mail de confirmation au client via PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuration du serveur SMTP (Exemple avec un serveur local ou Mailtrap pour les tests)
            // Pour du test local sans vrai serveur, tu peux utiliser un outil comme Mailtrap ou configurer un SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.mailtrap.io'; // Remplace par ton hôte SMTP ou ton serveur mail
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ton_utilisateur_smtp'; // Ton identifiant SMTP
            $mail->Password   = 'ton_mot_de_passe_smtp'; // Ton mot de passe SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 2525;

            // Expéditeur et Destinataire
            $mail->setFrom('contact@vite-et-gourmand.fr', 'Vite & Gourmand');
            $mail->addAddress($email, $nom); 

            // Contenu du mail
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de votre commande - Vite & Gourmand';
            $mail->Body    = "<h1>Merci pour votre commande, " . htmlspecialchars($nom) . " !</h1>
                              <p>Votre commande pour <b>" . $convives . " convives</b> a bien été prise en compte.</p>
                              <p>Montant total (incluant la livraison) : <b>" . number_format($total_final, 2, ',', ' ') . " €</b></p>
                              <p>Statut actuel : <i>En attente de validation</i></p>
                              <p>À très bientôt chez Vite & Gourmand !</p>";
            
            $mail->AltBody = "Merci pour votre commande ! Total : " . $total_final . " €.";

            $mail->send();
        } catch (Exception $e) {
            // L'e-mail a échoué mais la commande est enregistrée, on peut logger l'erreur si besoin
            error_log("Erreur d'envoi d'e-mail : {$mail->ErrorInfo}");
        }

        // 3. Redirection vers la page de succès
        header('Location: mes_commandes.php?success=1');
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement de la commande : " . $e->getMessage());
    }
}
?>