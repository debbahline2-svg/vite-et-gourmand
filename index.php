<?php 
require_once 'includes/header.php'; 
$estConnecte = isset($_SESSION['user_id']);
?>

<style>
    :root {
        --accent-gold: #C17F3A; 
        --deep-ebony: #12100E; 
        --dark-oak: #2D231A;   
        --antique-white: #FAF9F6; 
    }

    body {
        background-color: var(--deep-ebony);
        color: var(--antique-white);
        font-family: 'Poppins', sans-serif;
    }

    h1, h2, h3, h4, .serif-font {
        font-family: 'Playfair Display', serif;
    }

    /* Hero : Ambiance Prestige */
    .hero-academia {
        background: linear-gradient(rgba(18, 16, 14, 0.85), rgba(18, 16, 14, 0.85)), 
                    url('https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=2000&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        padding: 140px 0;
        border-bottom: 1px solid rgba(193, 127, 58, 0.2);
    }

    /* Cartes Philosophie */
    .card-academia {
        background: rgba(45, 35, 26, 0.4);
        border: 1px solid rgba(193, 127, 58, 0.15);
        padding: 40px;
        transition: all 0.4s ease;
    }

    .card-academia:hover {
        border-color: var(--accent-gold);
        background: rgba(45, 35, 26, 0.7);
        transform: translateY(-5px);
    }

    /* Style des Avis (Récits) */
    .quote-card {
        background: transparent;
        border-left: 1px solid rgba(193, 127, 58, 0.4);
        padding: 20px 30px;
        height: 100%;
    }

    /* Bouton Call to Action */
    .btn-academia {
        background: transparent;
        color: var(--accent-gold);
        border: 1px solid var(--accent-gold);
        padding: 12px 35px;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 0.8rem;
        text-decoration: none;
        transition: 0.3s;
        display: inline-block;
    }

    .btn-academia:hover {
        background: var(--accent-gold);
        color: var(--deep-ebony);
    }

    .dimanche-status {
        color: var(--accent-gold);
        font-weight: 600;
        letter-spacing: 1px;
    }

    /* Zone de réponse du traiteur */
    .reponse-traiteur {
        background: rgba(193, 127, 58, 0.05);
        border-left: 1px solid var(--accent-gold);
        padding: 15px;
        margin-top: 20px;
        font-size: 0.8rem;
    }
</style>

<section class="hero-academia text-center">
    <div class="container">
        <h1 class="display-3 mb-4">L'Excellence du Goût</h1>
        <p class="fs-5 mb-5 opacity-75 fst-italic" style="max-width: 650px; margin: 0 auto;">
            Julie & José perpétuent l'art culinaire à Bordeaux à travers des créations authentiques et raffinées.
        </p>
        <a href="pages/menus.php" class="btn-academia">Consulter nos cartes</a>
    </div>
</section>

<section class="py-5">
    <div class="container py-5">
        <h2 class="text-center mb-5 fw-light" style="letter-spacing: 3px;">NOTRE PHILOSOPHIE</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card-academia h-100">
                    <h3 class="h4 mb-3">Héritage Culinaire</h3>
                    <p class="small opacity-50">Vingt-cinq années dédiées à la maîtrise parfaite des produits et des cuissons.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-academia h-100">
                    <h3 class="h4 mb-3">Terroir Bordelais</h3>
                    <p class="small opacity-50">Une sélection rigoureuse de produits de saison issus de nos jardins locaux.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-academia h-100">
                    <h3 class="h4 mb-3">Excellence HACCP</h3>
                    <p class="small opacity-50">Chaque préparation est conçue dans le respect absolu des normes de haute gastronomie.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background: rgba(255,255,255,0.01);">
    <div class="container py-5">
        <h2 class="text-center mb-5 fw-light" style="letter-spacing: 3px;">RÉCITS DE RÉCEPTIONS</h2>
        <div class="row g-5">
            <div class="col-md-4">
                <div class="quote-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div style="color: var(--accent-gold); font-size: 0.8rem;">★★★★★</div>
                        <small class="opacity-25" style="font-size: 0.7rem;">Il y a 2 semaines</small>
                    </div>
                    <p class="fst-italic opacity-75 small">"Une expérience incroyable et authentique, personnels sérieux et agréable merci !!"</p>
                    <h4 class="h6 mt-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">— Lily M.</h4>
                </div>
            </div>

            <div class="col-md-4">
                <div class="quote-card shadow-sm" style="border-color: var(--accent-gold);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div style="color: var(--accent-gold); font-size: 0.8rem;">★★★★★</div>
                        <small class="opacity-25" style="font-size: 0.7rem;">Le 12 Mars 2026</small>
                    </div>
                    <p class="fst-italic opacity-75 small">"Parfait pour notre baby shower, une expérience inoubliable pour tous nos invités."</p>
                    <h4 class="h6 mt-2 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">— Harry D.</h4>
                    
                    <div class="reponse-traiteur">
                        <p class="mb-1 fw-bold" style="color: var(--accent-gold);">Réponse de Julie & José :</p>
                        <p class="mb-0 opacity-60 fst-italic">"Félicitations pour cet heureux événement à venir. Merci de nous avoir permis de contribuer à ce moment si précieux."</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="quote-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div style="color: var(--accent-gold); font-size: 0.8rem;">★★★★★</div>
                        <small class="opacity-25" style="font-size: 0.7rem;">Le 28 Janvier 2026</small>
                    </div>
                    <p class="fst-italic opacity-75 small">"Offre une grande variété de plats adaptés à plusieurs régimes alimentaires, nous recommandons vivement !"</p>
                    <h4 class="h6 mt-3 text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">— Vanessa P.</h4>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="border-top: 1px solid rgba(193, 127, 58, 0.1);">
    <div class="container">
        <div class="row text-center g-4 opacity-50" style="font-size: 0.7rem; letter-spacing: 2px;">
            <div class="col-md-4">📍 BORDEAUX & MÉTROPOLE</div>
            <div class="col-md-4">🍷 ACCORDS METS & VINS</div>
            <div class="col-md-4">✨ DEVIS PERSONNALISÉ SOUS 24H</div>
        </div>
    </div>
</section>

<section class="py-4" style="background: #0A0908; border-top: 1px solid rgba(193, 127, 58, 0.1);">
    <div class="container">
        <div class="row text-center align-items-center opacity-75" style="font-size: 0.85rem;">
            <div class="col-md-4">LUNDI — VENDREDI | 09:00 — 22:00</div>
            <div class="col-md-4">
                <h5 class="serif-font m-0" style="color: var(--accent-gold);">Vite & Gourmand</h5>
            </div>
            <div class="col-md-4">
                SAMEDI : 10:00 — 00:00 | <span class="dimanche-status">DIMANCHE : UNIQUEMENT SUR RÉSERVATION</span>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>