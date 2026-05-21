<footer class="mt-5 py-4" id="main-footer" style="background-color: #0c0b0a; border-top: 1px solid rgba(193, 127, 58, 0.2); color: #888;">
    <div class="container text-center">
        <p class="mb-1">© 2026 Vite & Gourmand — Tous droits réservés</p>
        
        <nav aria-label="Liens légaux">
            <a href="<?= $back ?>pages/mentions-legales.php" style="color: #C17F3A; text-decoration: none; font-size: 0.9rem;">Mentions légales</a>
            <span style="color: #333;">&nbsp;|&nbsp;</span>
            <a href="<?= $back ?>pages/cgv.php" style="color: #C17F3A; text-decoration: none; font-size: 0.9rem;">CGV</a>
            <span style="color: #333;">&nbsp;|&nbsp;</span>
            <a href="<?= $back ?>pages/contact.php" style="color: #C17F3A; text-decoration: none; font-size: 0.9rem;">Support</a>
        </nav>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // On s'assure que l'élément existe avant d'ajouter l'écouteur
    const btn = document.getElementById('btnDalton');
    
    if (btn) {
        btn.addEventListener('click', () => {
            document.body.classList.toggle('dalton-mode');
            const active = document.body.classList.contains('dalton-mode');
            localStorage.setItem('daltonien', active);
            btn.textContent = active ? "Mode Classique" : "Mode Daltonien";
        });

        // Au chargement, on vérifie si le mode était activé
        if (localStorage.getItem('daltonien') === 'true') {
            document.body.classList.add('dalton-mode');
            btn.textContent = "Mode Classique";
        }
    }
</script>

</body>
</html>