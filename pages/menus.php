<?php 
require_once '../includes/header.php'; 
require_once '../includes/database.php'; 
require_once '../includes/services/MenuService.php';

$database = new Database();
$db = $database->getConnection();
$menuService = new MenuService($db);
$menus = $menuService->getAllMenus();
?>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400&display=swap" rel="stylesheet">

<style>
    body { background-color: #0c0b0a; color: white; font-family: 'Poppins', sans-serif; }
    
    .main-title { 
        font-family: 'Playfair Display', serif; 
        color: #C17F3A; 
        font-size: clamp(2rem, 5vw, 3.5rem); 
        text-align: center;
        margin-top: 50px;
    }

    /* Boîtier de filtres élargi pour les demandes Studi */
    .filter-bar { 
        background: #161513; 
        padding: 25px; 
        border-radius: 10px; 
        margin: 30px auto; 
        max-width: 800px; 
        border: 1px solid #2D231A; 
    }

    .card-container { 
        display: flex; 
        justify-content: center; 
        gap: 20px; 
        flex-wrap: wrap; 
        padding: 10px; 
    }

    .menu-card { 
        background: #12100E; 
        border: 1px solid #2D231A; 
        width: 100%; 
        max-width: 350px; 
        padding: 25px; 
        text-align: center; 
        border-radius: 8px; 
        position: relative;
        transition: 0.3s;
    }

    .menu-card h2 { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #fff; }
    .price { color: #C17F3A; font-size: 1.8rem; font-weight: bold; margin: 15px 0; }
    
    .badge-theme { 
        background: #C17F3A; color: black; font-size: 0.7rem; padding: 4px 10px; 
        position: absolute; top: 15px; right: 10px; font-weight: bold; text-transform: uppercase;
    }

    .btn-reserve { 
        background: #C17F3A; color: black; text-decoration: none; padding: 15px; 
        display: block; font-weight: bold; border-radius: 5px; margin-top: 15px;
        transition: 0.3s;
    }
    .btn-reserve:hover { background: white; cursor: pointer; }

    /* Styles dédiés à la nouvelle section d'options culinaires */
    .option-card:hover {
        transform: translateY(-8px);
        border-color: #C17F3A !important;
        box-shadow: 0 10px 20px rgba(193, 127, 58, 0.15);
    }
    .option-card:hover .option-img-wrapper img {
        transform: scale(1.08);
    }

    @media (max-width: 600px) {
        .filter-bar { width: 90%; }
        .menu-card { max-width: 100%; }
    }
</style>

<main class="container">
    <h1 class="main-title">Nos Cartes Gastronomiques</h1>

    <div class="filter-bar">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small text-muted">Recherche</label>
                <input type="text" id="searchFilter" class="form-control bg-dark text-white border-secondary" placeholder="Nom du menu...">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">Thème</label>
                <select id="themeFilter" class="form-select bg-dark text-white border-secondary">
                    <option value="">Tous les thèmes</option>
                    <option value="Évènement">Évènement</option>
                    <option value="Noël">Noël</option>
                    <option value="Classique">Classique</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">Prix max / pers.</label>
                <input type="number" id="priceFilter" class="form-control bg-dark text-white border-secondary" placeholder="Ex: 100">
            </div>
        </div>
    </div>

    <div class="card-container" id="menuContainer">
        <?php foreach ($menus as $m): 
            $prix_menu = isset($m['prix']) ? $m['prix'] : ($m['prix_base'] ?? 0);
            $theme_menu = $m['theme'] ?? 'Classique';
        ?>
        <div class="menu-card" 
             data-titre="<?= strtolower(htmlspecialchars($m['titre'])) ?>" 
             data-theme="<?= htmlspecialchars($theme_menu) ?>" 
             data-prix="<?= floatval($prix_menu) ?>">
            
            <div class="badge-theme"><?= htmlspecialchars($theme_menu) ?></div>
            
            <h2><?= htmlspecialchars($m['titre']) ?></h2>
            
            <p style="color: #aaa; font-size: 0.9rem; min-height: 45px;">
                <?= htmlspecialchars($m['description']) ?>
            </p>

            <div class="price"><?= number_format($prix_menu, 2, ',', ' ') ?>€ <small style="font-size: 0.8rem; opacity: 0.7;">/ pers</small></div>
            
            <a href="detail-menu.php?id=<?= $m['id'] ?>" style="color: #888; font-size: 0.8rem; text-decoration: underline;">Détails & Allergènes</a>

            <a href="commande.php?id=<?= $m['id'] ?>" class="btn-reserve">RÉSERVER CE MENU</a>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div id="noMatchMessage" class="text-center mt-4 text-muted" style="display: none;">
        Aucun menu ne correspond à vos critères de recherche.
    </div>

    <hr style="border-color: rgba(193, 127, 58, 0.2); margin: 70px 0 50px 0;">

    <section class="mb-5">
        <div class="text-center mb-5">
            <h2 style="font-family: 'Playfair Display', serif; color: #C17F3A; font-weight: 700; font-size: 2.2rem;">
                Personnalisez Votre Événement
            </h2>
            <p style="color: #aaa; font-style: italic; max-width: 600px; margin: 10px auto 0 auto; font-size: 0.9rem;">
                Découvrez nos ateliers complémentaires pour concevoir un moment gastronomique unique et sur-mesure.
            </p>
            <div style="width: 50px; height: 1px; background: #C17F3A; margin: 15px auto 0 auto;"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="option-card" style="background: #161513; border: 1px solid rgba(193, 127, 58, 0.15); border-radius: 12px; overflow: hidden; height: 100%; transition: 0.3s; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="option-img-wrapper" style="position: relative; height: 180px; overflow: hidden;">
                            <img src="https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?q=80&w=500&auto=format&fit=crop" alt="Accords Mets & Vins" style="width: 100%; height: 100%; object-fit: cover; transition: 0.4s;">
                            <div style="position: absolute; top: 12px; left: 12px; background: #C17F3A; color: black; font-weight: bold; font-size: 0.65rem; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Cave</div>
                        </div>
                        <div style="padding: 20px 20px 10px 20px;">
                            <h4 style="font-family: 'Playfair Display', serif; color: #C17F3A; font-size: 1.15rem; margin-bottom: 10px;">Accords Mets & Vins</h4>
                            <p style="color: #bbb; font-size: 0.8rem; line-height: 1.5; margin-bottom: 0;">
                                Une sélection de cépages et de grands crus français ajustée à chaque étape de votre menu par nos sommeliers.
                            </p>
                        </div>
                    </div>
                    <div style="padding: 0 20px 20px 20px; text-align: center;">
                        <a href="detail-option.php?type=cave" style="color: #888; font-size: 0.8rem; text-decoration: underline;">Exemples & Détails</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="option-card" style="background: #161513; border: 1px solid rgba(193, 127, 58, 0.15); border-radius: 12px; overflow: hidden; height: 100%; transition: 0.3s; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="option-img-wrapper" style="position: relative; height: 180px; overflow: hidden;">
                            <img src="https://images.unsplash.com/photo-1541532713592-79a0317b6b77?q=80&w=500&auto=format&fit=crop" alt="Cocktails & Apéritifs" style="width: 100%; height: 100%; object-fit: cover; transition: 0.4s;">
                            <div style="position: absolute; top: 12px; left: 12px; background: #C17F3A; color: black; font-weight: bold; font-size: 0.65rem; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Cocktail</div>
                        </div>
                        <div style="padding: 20px 20px 10px 20px;">
                            <h4 style="font-family: 'Playfair Display', serif; color: #C17F3A; font-size: 1.15rem; margin-bottom: 10px;">Cocktails & Pièces</h4>
                            <p style="color: #bbb; font-size: 0.8rem; line-height: 1.5; margin-bottom: 0;">
                                Ateliers de pièces dînatoires créatives, animations culinaires en direct et bars à cocktails premium.
                            </p>
                        </div>
                    </div>
                    <div style="padding: 0 20px 20px 20px; text-align: center;">
                        <a href="detail-option.php?type=cocktail" style="color: #888; font-size: 0.8rem; text-decoration: underline;">Exemples & Détails</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="option-card" style="background: #161513; border: 1px solid rgba(193, 127, 58, 0.15); border-radius: 12px; overflow: hidden; height: 100%; transition: 0.3s; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="option-img-wrapper" style="position: relative; height: 180px; overflow: hidden;">
                            <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=500&auto=format&fit=crop" alt="Variations Culinaires" style="width: 100%; height: 100%; object-fit: cover; transition: 0.4s;">
                            <div style="position: absolute; top: 12px; left: 12px; background: #C17F3A; color: black; font-weight: bold; font-size: 0.65rem; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Régimes</div>
                        </div>
                        <div style="padding: 20px 20px 10px 20px;">
                            <h4 style="font-family: 'Playfair Display', serif; color: #C17F3A; font-size: 1.15rem; margin-bottom: 10px;">Régimes Spécifiques</h4>
                            <p style="color: #bbb; font-size: 0.8rem; line-height: 1.5; margin-bottom: 0;">
                                Déclinaisons gastronomiques adaptées : propositions végétariennes, sans gluten, sans porc ou halal sur demande.
                            </p>
                        </div>
                    </div>
                    <div style="padding: 0 20px 20px 20px; text-align: center;">
                        <a href="detail-option.php?type=regimes" style="color: #888; font-size: 0.8rem; text-decoration: underline;">Exemples & Détails</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="option-card" style="background: #161513; border: 1px solid rgba(193, 127, 58, 0.15); border-radius: 12px; overflow: hidden; height: 100%; transition: 0.3s; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div class="option-img-wrapper" style="position: relative; height: 180px; overflow: hidden;">
                            <img src="https://images.unsplash.com/photo-1578474846511-04ba529f0b88?q=80&w=500&auto=format&fit=crop" alt="Art de la table & Service" style="width: 100%; height: 100%; object-fit: cover; transition: 0.4s;">
                            <div style="position: absolute; top: 12px; left: 12px; background: #C17F3A; color: black; font-weight: bold; font-size: 0.65rem; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">Clé en main</div>
                        </div>
                        <div style="padding: 20px 20px 10px 20px;">
                            <h4 style="font-family: 'Playfair Display', serif; color: #C17F3A; font-size: 1.15rem; margin-bottom: 10px;">Nappage & Service</h4>
                            <p style="color: #bbb; font-size: 0.8rem; line-height: 1.5; margin-bottom: 0;">
                                Prise en charge intégrale : arts de la table précieux, location de vaisselle et maîtres d'hôtel professionnels.
                            </p>
                        </div>
                    </div>
                    <div style="padding: 0 20px 20px 20px; text-align: center;">
                        <a href="detail-option.php?type=service" style="color: #888; font-size: 0.8rem; text-decoration: underline;">Exemples & Détails</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="contact.php" class="btn" style="background: #C17F3A; color: black; font-weight: bold; padding: 12px 35px; border-raaddEventListenerdius: 30px; text-transform: uppercase; text-decoration: none; display: inline-block; font-size: 0.85rem; letter-spacing: 0.5px; transition: 0.3s;">
                Demander un devis sur-mesure
            </a>
        </div>
    </section>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchFilter = document.getElementById('searchFilter');
    const themeFilter = document.getElementById('themeFilter');
    const priceFilter = document.getElementById('priceFilter');
    const cards = document.querySelectorAll('.menu-card');
    const noMatchMessage = document.getElementById('noMatchMessage');

    function filtrerLesMenus() {
        const searchText = searchFilter.value.toLowerCase().trim();
        const selectedTheme = themeFilter.value;
        const maxPrice = parseFloat(priceFilter.value) || Infinity;
        
        let targetVisible = 0;

        cards.forEach(card => {
            const cardTitre = card.getAttribute('data-titre');
            const cardTheme = card.getAttribute('data-theme');
            const cardPrix = parseFloat(card.getAttribute('data-prix'));

            const matchSearch = cardTitre.includes(searchText);
            const matchTheme = selectedTheme === "" || cardTheme === selectedTheme;
            const matchPrice = cardPrix <= maxPrice;

            if (matchSearch && matchTheme && matchPrice) {
                card.style.display = "block";
                targetVisible++;
            } else {
                card.style.display = "none";
            }
        });

        if (targetVisible === 0) {
            noMatchMessage.style.display = "block";
        } else {
            noMatchMessage.style.display = "none";
        }
    }

    searchFilter.addEventListener('input', filtrerLesMenus);
    themeFilter.addEventListener('change', filtrerLesMenus);
    priceFilter.addEventListener('input', filtrerLesMenus);
});
</script>

<?php require_once '../includes/footer.php'; ?>