<?php 
require_once '../includes/header.php'; 
require_once '../includes/database.php'; 

// Récupération sécurisée du type d'option
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

// Structure de données stable avec images Pixabay pérennes
// Tableau d'options 100% libres de droits et adaptées au haut de gamme 
 $options = [ 
     'cave' => [ 
         'titre' => 'Accords Mets & Vins', 

         'description' => 'Une sélection de cépages et de grands crus français ajustée à chaque étape de votre menu par nos sommeliers.', 
         'etape1_label' => 'Apéritif & Accueil', 
         'etape1_nom' => 'Champagne Brut Réserve d’Exception', 

         'etape1_img' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Champagne_glass.jpg/640px-Champagne_glass.jpg',// Dans votre tableau $options
'etape1_img' => '../assets/img/champagne.jpg',
 
         'etape2_label' => 'Pour les Entrées & Poissons', 
         'etape2_nom' => 'Chablis Premier Cru - Blanc Élégant', 

         'etape2_img' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?q=80&w=600&auto=format&fit=crop',
 
         'etape3_label' => 'Pour les Plats & Fromages', 
         'etape3_nom' => 'Saint-Émilion Grand Cru - Rouge Intense', 

         'etape3_img' => 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?q=80&w=600&auto=format&fit=crop'
 
     ], 
     'cocktail' => [ 
         'titre' => 'Cocktails & Pièces Culinaires', 

         'description' => 'Ateliers de pièces dînatoires créatives, animations culinaires en direct et bars à cocktails premium.', 
         'etape1_label' => 'Pièces Salées', 
         'etape1_nom' => 'Petits Fours Feuilletés et Canapés Fins', 

         'etape1_img' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600&auto=format&fit=crop',
 
         'etape2_label' => 'Mixologie Premium', 
         'etape2_nom' => 'Cocktails Signatures Créations Maison', 

         'etape2_img' => 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=600&auto=format&fit=crop',
 
         'etape3_label' => 'Mignardises Sucrées', 
         'etape3_nom' => 'Macarons, Choux et Canelés Artisanaux', 

         'etape3_img' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?q=80&w=600&auto=format&fit=crop'
 
     ], 
     'regimes' => [ 
         'titre' => 'Régimes Spécifiques', 

         'description' => 'Déclinaisons gastronomiques adaptées : propositions végétariennes, sans gluten, sans porc ou halal sur demande.', 
         'etape1_label' => 'Alternative Végétarienne', 
         'etape1_nom' => 'Mise en Bouche Maraîchère & Velouté de Saison', 

         'etape1_img' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=600&auto=format&fit=crop',
 
         'etape2_label' => 'Option Sans Gluten', 
         'etape2_nom' => 'Suprême Végétal aux Épices Douces', 

         'etape2_img' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop',
 
         'etape3_label' => 'Douceur Inclusive', 
         'etape3_nom' => 'Pavlova aux Fruits Frais (Sans gélatine animale)', 

         'etape3_img' => 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?q=80&w=600&auto=format&fit=crop'
 
     ], 
     'service' => [ 
         'titre' => 'Nappage & Service', 

         'description' => 'Prise en charge intégrale : arts de la table précieux, location de vaisselle et maîtres d’hôtel professionnels.', 
         'etape1_label' => 'Art de la Table', 
         'etape1_nom' => 'Dressage d’Honneur : Cristal, Porcelaine & Lin', 

         'etape1_img' => 'https://images.unsplash.com/photo-1578474846511-04ba529f0b88?q=80&w=600&auto=format&fit=crop',
 
         'etape2_label' => 'Personnel de Maison', 
         'etape2_nom' => 'Service à l’Assiette par nos Maîtres d’Hôtel', 

         'etape2_img' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?q=80&w=600&auto=format&fit=crop',
 
         'etape3_label' => 'Prise en Charge Clé en Main', 
         'etape3_nom' => 'Logistique, Débarrassage et Nettoyage Intégral', 

         'etape3_img' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=600&auto=format&fit=crop'
 
     ] 
 ];

// Gestion erreur si le type n'existe pas
if (!array_key_exists($type, $options)) {
    echo "<div style='color:white; text-align:center; padding:100px;'><h1>Option introuvable</h1><a href='menus.php' style='color:#C17F3A;'>Retour aux menus</a></div>";
    require_once '../includes/footer.php';
    exit;
}

$opt = $options[$type];
?>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400&display=swap" rel="stylesheet">

<style>
    body { background-color: #0c0b0a; color: white; font-family: 'Poppins', sans-serif; }
    .menu-container { max-width: 800px; margin: 50px auto; background: #12100E; padding: 50px; border: 1px solid #C17F3A; text-align: center; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    .gold-line { width: 60px; height: 2px; background: #C17F3A; margin: 20px auto; }
    .course { margin-bottom: 40px; }
    .dish-img { width: 200px; height: 200px; object-fit: cover; border-radius: 50%; border: 3px solid #C17F3A; margin-bottom: 15px; display: block; margin: 0 auto 15px auto; }
    .course-title { color: #C17F3A; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 2px; display: block; margin-bottom: 10px; font-weight: bold; }
    .price-large { font-size: 1.8rem; color: #C17F3A; margin: 30px 0; font-family: 'Playfair Display', serif; font-style: italic; }
    .btn-order { background: #C17F3A; color: black; font-weight: bold; padding: 15px 40px; text-decoration: none; display: inline-block; text-transform: uppercase; transition: 0.3s; border-radius: 5px; }
    .btn-order:hover { background: white; cursor: pointer; }
</style>

<div class="container">
    <div class="menu-container">
        <h1 style="font-family: 'Playfair Display', serif; color: #C17F3A;"><?php echo htmlspecialchars($opt['titre']); ?></h1>
        <p><i><?php echo htmlspecialchars($opt['description']); ?></i></p>
        <div class="gold-line"></div>

        <div class="course">
            <span class="course-title"><?php echo htmlspecialchars($opt['etape1_label']); ?></span>
            <img src="<?php echo $opt['etape1_img']; ?>" class="dish-img" alt="Illustration">
            <h3><?php echo htmlspecialchars($opt['etape1_nom']); ?></h3>
        </div>

        <div class="course">
            <span class="course-title"><?php echo htmlspecialchars($opt['etape2_label']); ?></span>
            <img src="<?php echo $opt['etape2_img']; ?>" class="dish-img" alt="Illustration">
            <h3><?php echo htmlspecialchars($opt['etape2_nom']); ?></h3>
        </div>

        <div class="course">
            <span class="course-title"><?php echo htmlspecialchars($opt['etape3_label']); ?></span>
            <img src="<?php echo $opt['etape3_img']; ?>" class="dish-img" alt="Illustration">
            <h3><?php echo htmlspecialchars($opt['etape3_nom']); ?></h3>
        </div>

        <div class="price-large">Tarifs sur devis</div>
        <a href="contact.php" class="btn-order">Demander un devis personnalisé</a>
        
        <div style="margin-top: 30px;">
            <a href="menus.php" style="color: #888; text-decoration: none; font-size: 0.8rem; letter-spacing: 1px;">← RETOUR AUX MENUS</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>