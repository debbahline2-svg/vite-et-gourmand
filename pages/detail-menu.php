 <?php

require_once '../includes/header.php';

require_once '../includes/db.php';


// On récupère le paramètre 'id' de l'URL

$id_param = isset($_GET['id']) ? trim($_GET['id']) : '0';


// Tableau de données synchronisé avec tes 4 options du bas

$options_sur_mesure = [

    'cave' => [

        'titre' => 'Accords Mets & Vins',

        'description' => 'Une sélection de cépages et de grands crus français ajustée à chaque étape de votre menu par nos sommeliers.',

        'entree_label' => 'Apéritif & Accueil',

        'entree_nom' => 'Champagne Brut Réserve d’Exception',

        'entree_img_url' => 'https://images.unsplash.com/photo-1594498653385-d5172b532c00?q=80&w=600&auto=format&fit=crop',

        'plat_label' => 'Pour les Entrées & Poissons',

        'plat_nom' => 'Chablis Premier Cru - Blanc Élégant',

        'plat_img_url' => 'https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?q=80&w=600&auto=format&fit=crop',

        'dessert_label' => 'Pour les Plats & Fromages',

        'dessert_nom' => 'Saint-Émilion Grand Cru - Rouge Intense',

        'dessert_img_url' => 'https://images.unsplash.com/photo-1506377247377-2a5b3b417ebb?q=80&w=600&auto=format&fit=crop',

        'is_option' => true

    ],

    'cocktail' => [

        'titre' => 'Cocktails & Pièces Culinaires',

        'description' => 'Ateliers de pièces dînatoires créatives, animations culinaires en direct et bars à cocktails premium.',

        'entree_label' => 'Pièces Salées',

        'entree_nom' => 'Petits Fours Feuilletés et Canapés Fins',

        'entree_img_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600&auto=format&fit=crop',

        'plat_label' => 'Mixologie Premium',

        'plat_nom' => 'Cocktails Signatures Créations Maison',

        'plat_img_url' => 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=600&auto=format&fit=crop',

        'dessert_label' => 'Mignardises Sucrées',

        'dessert_nom' => 'Macarons, Choux et Canelés Artisanaux',

        'dessert_img_url' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?q=80&w=600&auto=format&fit=crop',

        'is_option' => true

    ],

    'regimes' => [

        'titre' => 'Régimes Spécifiques',

        'description' => 'Déclinaisons gastronomiques adaptées : propositions végétariennes, sans gluten, sans porc ou halal sur demande.',

        'entree_label' => 'Alternative Végétarienne',

        'entree_nom' => 'Mise en Bouche Maraîchère & Velouté de Saison',

        'entree_img_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=600&auto=format&fit=crop',

        'plat_label' => 'Option Sans Gluten',

        'plat_nom' => 'Suprême Végétal aux Épices Douces',

        'plat_img_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop',

        'dessert_label' => 'Douceur Inclusive',

        'dessert_nom' => 'Pavlova aux Fruits Frais (Sans gélatine animale)',

        'dessert_img_url' => 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?q=80&w=600&auto=format&fit=crop',

        'is_option' => true

    ],

    'service' => [

        'titre' => 'Nappage & Service',

        'description' => 'Prise en charge intégrale : arts de la table précieux, location de vaisselle et maîtres d’hôtel professionnels.',

        'entree_label' => 'Art de la Table',

        'entree_nom' => 'Dressage d’Honneur : Cristal, Porcelaine & Lin',

        'entree_img_url' => 'https://images.unsplash.com/photo-1578474846511-04ba529f0b88?q=80&w=600&auto=format&fit=crop',

        'plat_label' => 'Personnel de Maison',

        'plat_nom' => 'Service à l’Assiette par nos Maîtres d’Hôtel',

        'plat_img_url' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?q=80&w=600&auto=format&fit=crop',

        'dessert_label' => 'Prise en Charge Clé en Main',

        'dessert_nom' => 'Logistique, Débarrassage et Nettoyage Intégral',

        'dessert_img_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=600&auto=format&fit=crop',

        'is_option' => true

    ]

];


// Si le paramètre correspond à une de nos 4 options personnalisées

if (array_key_exists($id_param, $options_sur_mesure)) {

    $m = $options_sur_mesure[$id_param];

} else {

    // Sinon, on traite l'ID comme un entier pour les menus classiques (ex: id=1 pour Mariage)

    $id = intval($id_param);

    $database = new Database();

    $db = $database->getConnection();


    $stmt = $db->prepare("SELECT * FROM menus WHERE id = ?");

    $stmt->execute([$id]);

    $m = $stmt->fetch(PDO::FETCH_ASSOC);

}


// Si l'élément demandé n'existe pas du tout

if (!$m) {

    echo "<div style='color:white; text-align:center; padding:100px;'><h1>Élément introuvable</h1><a href='menus.php' style='color:#C17F3A;'>Retour aux menus</a></div>";

    require_once '../includes/footer.php';

    exit;

}

?>


<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400&display=swap" rel="stylesheet">


<style>

    body { background-color: #0c0b0a; color: white; font-family: 'Poppins', sans-serif; }

    .menu-container { max-width: 800px; margin: 50px auto; background: #12100E; padding: 50px; border: 1px solid #C17F3A; text-align: center; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }

    .gold-line { width: 60px; height: 2px; background: #C17F3A; margin: 20px auto; }

    .course { margin-bottom: 40px; }

    .dish-img { width: 200px; height: 200px; object-fit: cover; border-radius: 50%; border: 3px solid #C17F3A; margin-bottom: 15px; display: block; margin: 0 auto 15px auto; }

    .course-title { color: #C17F3A; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 2px; display: block; margin-bottom: 10px; font-weight: bold; }

    .price-large { font-size: 2.5rem; color: #C17F3A; margin: 30px 0; font-family: 'Playfair Display', serif; }

    .btn-order { background: #C17F3A; color: black; font-weight: bold; padding: 15px 40px; text-decoration: none; display: inline-block; text-transform: uppercase; transition: 0.3s; border-radius: 5px; }

    .btn-order:hover { background: white; cursor: pointer; }

</style>


<div class="container">

    <div class="menu-container">

        <h1 style="font-family: 'Playfair Display', serif; color: #C17F3A;"><?php echo htmlspecialchars($m['titre']); ?></h1>

        <p><i><?php echo htmlspecialchars($m['description']); ?></i></p>

        <div class="gold-line"></div>


        <div class="course">

            <span class="course-title">

                <?php echo isset($m['is_option']) ? htmlspecialchars($m['entree_label']) : 'Entrée'; ?>

            </span>

            <?php if (isset($m['is_option'])): ?>

                <img src="<?php echo $m['entree_img_url']; ?>" class="dish-img" alt="Illustration">

            <?php else: ?>

                <img src="../images/<?php echo htmlspecialchars($m['entree_img'] ?? 'default_entree.jpg'); ?>" class="dish-img" alt="Image de l'entrée">

            <?php endif; ?>

            <h3><?php echo htmlspecialchars($m['entree_nom'] ?? 'Entrée du chef'); ?></h3>

        </div>


        <div class="course">

            <span class="course-title">

                <?php echo isset($m['is_option']) ? htmlspecialchars($m['plat_label']) : 'Plat Principal'; ?>

            </span>

            <?php if (isset($m['is_option'])): ?>

                <img src="<?php echo $m['plat_img_url']; ?>" class="dish-img" alt="Illustration">

            <?php else: ?>

                <img src="../images/<?php echo htmlspecialchars($m['plat_img'] ?? 'default_plat.jpg'); ?>" class="dish-img" alt="Image du plat principal">

            <?php endif; ?>

            <h3><?php echo htmlspecialchars($m['plat_nom'] ?? 'Plat signature'); ?></h3>

        </div>


        <div class="course">

            <span class="course-title">

                <?php echo isset($m['is_option']) ? htmlspecialchars($m['dessert_label']) : 'Dessert'; ?>

            </span>

            <?php if (isset($m['is_option'])): ?>

                <img src="<?php echo $m['dessert_img_url']; ?>" class="dish-img" alt="Illustration">

            <?php else: ?>

                <img src="../images/<?php echo htmlspecialchars($m['dessert_img'] ?? 'default_dessert.jpg'); ?>" class="dish-img" alt="Image du dessert">

            <?php endif; ?>

            <h3><?php echo htmlspecialchars($m['dessert_nom'] ?? 'Douceur sucrée'); ?></h3>

        </div>


        <?php if (isset($m['is_option'])): ?>

            <div class="price-large" style="font-size: 1.8rem; font-style: italic; font-weight: normal;">Tarifs sur devis</div>

            <a href="contact.php" class="btn-order">Demander un devis</a>

        <?php else: ?>

            <div class="price-large"><?php echo number_format($m['prix'] ?? $m['prix_base'] ?? 0, 2, ',', ' '); ?> €</div>

            <a href="commande.php?id=<?php echo $m['id']; ?>" class="btn-order">Réserver ce menu</a>

        <?php endif; ?>

       

        <div style="margin-top: 30px;">

            <a href="menus.php" style="color: #888; text-decoration: none; font-size: 0.8rem; letter-spacing: 1px;">← RETOUR AUX MENUS</a>

        </div>

    </div>

</div>


<?php require_once '../includes/footer.php'; ?> 