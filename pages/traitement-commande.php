<?php
session_start();
require_once '../includes/db.php'; 

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = htmlspecialchars($_POST['nom']);
    $email    = htmlspecialchars($_POST['email']);
    $convives = intval($_POST['convives']);
    $ville    = htmlspecialchars($_POST['ville']); // On récupère la ville
    $distance = floatval($_POST['distance'] ?? 0); // La distance en km envoyée par le formulaire
    
    // CALCUL DU PRIX : 5,59€/km si ce n'est pas Bordeaux
    $prix_livraison = 0;
    if (strtolower($ville) !== 'bordeaux') {
        $prix_livraison = $distance * 5.59;
    }
    
    // Ici, tu ajoutes le prix de ton menu (exemple : 20€ par convive)
    $prix_menu = $convives * 20; 
    $total_final = $prix_menu + $prix_livraison;

    try {
        $sql = "INSERT INTO commandes (nom_client, email_client, convives, ville, distance, total_prix, date_commande) 
                VALUES (:nom, :email, :convives, :ville, :distance, :total, NOW())";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'nom'      => $nom,
            'email'    => $email,
            'convives' => $convives,
            'ville'    => $ville,
            'distance' => $distance,
            'total'    => $total_final
        ]);

        header('Location: mes_commandes.php?success=1');
        exit;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>