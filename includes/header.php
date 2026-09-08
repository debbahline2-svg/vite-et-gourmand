<?php
// Empêche l'erreur "session already started" si session_start() est appelé
// plusieurs fois dans la même requête (header.php peut être inclus depuis
// des fichiers qui ont déjà démarré leur propre session, comme profil.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// getcwd() renvoie le dossier de travail courant du script PHP en exécution.
// basename() en extrait juste le dernier segment (ex: '.../pages' -> 'pages')
// Ça permet à header.php d'adapter ses liens selon QUI l'inclut :
// - si le fichier appelant est dans pages/, pas besoin de préfixe 'pages/'
// - si le fichier appelant est à la racine (index.php), il faut préfixer 'pages/'
$is_pages = (basename(getcwd()) == 'pages');
$prefix = $is_pages ? "" : "pages/";  // pour construire les liens VERS pages/xxx.php
$back = $is_pages ? "../" : "";        // pour remonter vers la racine (index.php) si on est dans pages/
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>

    <!-- Bootstrap chargé via CDN : évite de gérer/mettre à jour le framework CSS
         manuellement dans le projet, et bénéficie de la mise en cache navigateur
         si l'utilisateur a déjà visité un autre site utilisant le même CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Polices Google Fonts : Playfair Display (titres élégants/serif) 
         et Roboto (texte courant/sans-serif), chargées une seule fois ici -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        /* Variables CSS custom : centralise les couleurs de la charte graphique.
           Si le doré change un jour, une seule ligne à modifier au lieu de
           chercher/remplacer #C17F3A partout dans le fichier */
        :root {
            --gold: #C17F3A;
            --dark-bg: #0c0b0a;
        }

        /* --- LOGIQUE DALTONIEN (accessibilité) --- */
        /* filter CSS appliqué à TOUT le body : augmente la saturation (200%)
           et le contraste (110%) pour aider les personnes daltoniennes
           à mieux distinguer les couleurs proches (ex: rouge/vert) */
        body.dalton-mode {
            filter: saturate(200%) contrast(110%);
        }
        /* En mode daltonien, on force certains éléments clés en jaune vif (#FFD700)
           car le doré normal (#C17F3A) peut être difficile à distinguer du fond sombre
           même avec le filtre de saturation appliqué */
        body.dalton-mode .navbar-brand, 
        body.dalton-mode .nav-link:hover,
        body.dalton-mode .gold-text {
            color: #FFD700 !important;
        }
        /* Le bouton lui-même passe en noir/blanc à fort contraste en mode daltonien,
           pour rester lisible même avec le filtre appliqué */
        body.dalton-mode .btn-daltonien {
            background-color: #FFFFFF !important;
            color: #000000 !important;
            border: 2px solid #000 !important;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--dark-bg);
            color: white;
            margin: 0;
            /* transition sur 'filter' : rend le passage entre mode normal et
               mode daltonien fluide (0.3s) au lieu d'un changement brutal */
            transition: filter 0.3s ease;
        }
        .navbar {
            background-color: #0c0b0a !important;
            border-bottom: 1px solid rgba(193, 127, 58, 0.3);
            padding: 15px 0;
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            color: var(--gold) !important;
            font-weight: bold;
            font-size: 1.5rem;
            text-decoration: none;
        }
        .nav-link {
            color: white !important;
            text-transform: uppercase;
            font-size: 0.85rem;
            font-weight: 700;
            margin: 0 10px;
        }
        .nav-link:hover {
            color: var(--gold) !important;
        }
        .btn-daltonien {
            background-color: var(--gold);
            color: black;
            border: none;
            border-radius: 20px;
            padding: 4px 15px;
            font-size: 11px;
            font-weight: bold;
            margin-right: 20px;
            cursor: pointer;
            transition: 0.2s;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <!-- $back : lien vers l'accueil, adapté selon la position du fichier appelant -->
        <a class="navbar-brand" href="<?= $back ?>index.php">VITE & GOURMAND</a>
        
        <!-- Bouton qui active/désactive le mode daltonien (logique JS dans footer.php) -->
        <button id="btnDalton" class="btn-daltonien">Mode Accentué</button>
        
        <div class="collapse navbar-collapse show">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="<?= $back ?>index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>menus.php">Nos menus</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>contact.php">Contact</a></li>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <!-- Bloc affiché UNIQUEMENT si l'utilisateur est connecté -->
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <!-- Lien ADMIN visible seulement pour les comptes admin -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $prefix ?>admin_dashboard.php" style="color: #FFC107 !important;">ADMIN</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employe') : ?>
                        <!-- Lien COMMANDES visible seulement pour les comptes employé -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $prefix ?>employe_dashboard.php" style="color: #0dcaf0 !important;">COMMANDES</a>
                        </li>
                    <?php endif; ?>

                    <!-- Ces 2 liens sont visibles pour TOUT utilisateur connecté,
                         peu importe son rôle (client, employé, admin) -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $prefix ?>profil.php" style="color: var(--gold) !important;">MON PROFIL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small text-muted" href="<?= $prefix ?>logout.php">Déconnexion</a>
                    </li>

                <?php else : ?>
                    <!-- Bloc affiché si PERSONNE n'est connecté -->
                    <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>login.php">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>register.php">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>