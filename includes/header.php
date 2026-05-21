<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// On détecte si on est dans le dossier 'pages' pour ajuster les liens
$is_pages = (basename(getcwd()) == 'pages');
$prefix = $is_pages ? "" : "pages/";
$back = $is_pages ? "../" : "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #C17F3A;
            --dark-bg: #0c0b0a;
        }

        /* --- LOGIQUE DALTONIEN --- */
        body.dalton-mode {
            filter: saturate(200%) contrast(110%);
        }
        body.dalton-mode .navbar-brand, 
        body.dalton-mode .nav-link:hover,
        body.dalton-mode .gold-text {
            color: #FFD700 !important;
        }
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
        <a class="navbar-brand" href="<?= $back ?>index.php">VITE & GOURMAND</a>
        
        <button id="btnDalton" class="btn-daltonien">Mode Daltonien</button>
        
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="<?= $back ?>index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>menus.php">Nos menus</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>contact.php">Contact</a></li>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    
                    <?php if ($_SESSION['role'] === 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $prefix ?>admin_dashboard.php" style="color: #FFC107 !important;">ADMIN</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] === 'employe') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $prefix ?>employe_dashboard.php" style="color: #0dcaf0 !important;">COMMANDES</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= $prefix ?>profil.php" style="color: var(--gold) !important;">MON PROFIL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link small text-muted" href="<?= $prefix ?>logout.php">Déconnexion</a>
                    </li>

                <?php else : ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>login.php">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $prefix ?>register.php">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>