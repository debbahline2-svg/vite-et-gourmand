<?php 
require_once '../includes/header.php'; 
require_once '../includes/database.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $nb_min = $_POST['nb_pers_min'];
    $image = $_POST['image']; // en gros on tape fichier image

    $query = "INSERT INTO menus (titre, description, prix, nb_pers_min, image) 
              VALUES (:t, :d, :p, :m, :i)";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':t' => $titre,
        ':d' => $description,
        ':p' => $prix,
        ':m' => $nb_min,
        ':i' => $image
    ]);
    header('Location: admin_menus.php?msg=Menu ajouté !');
}
?>

<div class="container mt-5">
    <div class="card bg-dark text-white border-warning p-4 mx-auto" style="max-width: 600px;">
        <h2 class="text-warning mb-4">Nouveau Menu</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Titre du menu</label>
                <input type="text" name="titre" class="form-control bg-secondary text-white" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control bg-secondary text-white" rows="3"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Prix par personne</label>
                    <input type="number" step="0.01" name="prix" class="form-control bg-secondary text-white" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Convives Minimum</label>
                    <input type="number" name="nb_pers_min" class="form-control bg-secondary text-white" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Nom du fichier image (ex: buffet.jpg)</label>
                <input type="text" name="image" class="form-control bg-secondary text-white" value="default.jpg">
            </div>
            <button type="submit" class="btn btn-warning w-100">Enregistrer le menu</button>
        </form>
    </div>
</div>