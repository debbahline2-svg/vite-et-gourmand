<footer class="mt-5 py-4" id="main-footer" style="background-color: #0c0b0a; border-top: 1px solid rgba(193, 127, 58, 0.2); color: #888;">
    <div class="container text-center">
        <p class="mb-1">© 2026 Vite & Gourmand — Tous droits réservés</p>
        
        <!-- aria-label : attribut d'accessibilité qui donne un nom explicite
             à ce groupe de liens pour les lecteurs d'écran (technologie
             d'assistance utilisée par les personnes malvoyantes) -->
        <nav aria-label="Liens légaux">
            <!-- $back est réutilisé ici : cette variable a été définie dans header.php
                 et reste accessible car footer.php est inclus dans le MÊME contexte
                 d'exécution PHP (même requête, mêmes variables en mémoire) -->
            <a href="<?= $back ?>pages/mentions-legales.php" style="color: #C17F3A; text-decoration: none; font-size: 0.9rem;">Mentions légales</a>
            <span style="color: #333;">&nbsp;|&nbsp;</span>
            <a href="<?= $back ?>pages/cgv.php" style="color: #C17F3A; text-decoration: none; font-size: 0.9rem;">CGV</a>
            <span style="color: #333;">&nbsp;|&nbsp;</span>
            <a href="<?= $back ?>pages/contact.php" style="color: #C17F3A; text-decoration: none; font-size: 0.9rem;">Support</a>
        </nav>
    </div>
</footer>

<!-- Le JS de Bootstrap (dropdowns, menu mobile, etc.) est chargé ici, en bas de page,
     plutôt que dans le <head> : bonne pratique de performance, ça évite de bloquer
     le rendu visuel de la page pendant le téléchargement du script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // On récupère le bouton "Mode Accentué" défini dans header.php
    const btn = document.getElementById('btnDalton');
    
    // Vérification défensive : si jamais le bouton n'existe pas sur une page
    // (peu probable ici puisqu'il est dans header.php inclus partout),
    // ce garde-fou évite une erreur JS "Cannot read property of null"
    if (btn) {
        btn.addEventListener('click', () => {
            // toggle() : ajoute la classe si absente, la retire si présente.
            // C'est cette classe 'dalton-mode' qui déclenche le filter CSS
            // défini dans header.php
            document.body.classList.toggle('dalton-mode');
            
            const active = document.body.classList.contains('dalton-mode');
            
            // localStorage : stockage persistant côté navigateur (contrairement à
            // sessionStorage qui serait effacé à la fermeture de l'onglet).
            // Permet de mémoriser la préférence même après avoir quitté le site.
            // Note : localStorage ne stocke que des chaînes de caractères,
            // 'active' (booléen true/false) est donc automatiquement converti en "true"/"false"
            localStorage.setItem('daltonien', active);
            
            // Change le texte du bouton pour refléter l'état actuel
            btn.textContent = active ? "Mode Classique" : "Mode Accentué";
        });

        // Au chargement de CHAQUE page (puisque ce script est dans footer.php,
        // inclus partout), on relit localStorage pour réappliquer le mode
        // si l'utilisateur l'avait activé sur une page précédente.
        // Sans ça, le mode daltonien se "désactiverait" à chaque changement de page.
        if (localStorage.getItem('daltonien') === 'true') {
            document.body.classList.add('dalton-mode');
            btn.textContent = "Mode Classique";
        }
    }
</script>

</body>
</html>