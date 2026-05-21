<?php
session_start();
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. On récupère les noms exacts champs <input>
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $evenement = htmlspecialchars($_POST['event']);
    $convives = intval($_POST['convives']);
    $message = htmlspecialchars($_POST['message']);
    $date_evenement = $_POST['date_event']; 

    try {
        // 2. Utilisation de 'convives' 
        $sql = "INSERT INTO commandes (nom_client, email_client, convives, date_evenement, message, date_commande) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $pdo->prepare($sql);
        
        // On fusionne le type d'événement dans le message pour Julie
        $message_final = "Type : " . $evenement . " | " . $message;
        
        $stmt->execute([$nom, $email, $convives, $date_evenement, $message_final]);

        // 3. Succès ! Retour à l'accueil
        header('Location: ../pages/contact.php?success=1');
        exit;

    } catch (PDOException $e) {
        die("Erreur lors de l'enregistrement : " . $e->getMessage());
    }
}