document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('avisForm');
    const messageBox = document.getElementById('avisMessage');

    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Empêche le rechargement de la page

        const idCommande = form.dataset.idCommande;
        const formData = new FormData(form);

        fetch(`api/submit_avis.php?id_commande=${idCommande}`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                messageBox.textContent = data.message;
                messageBox.className = data.success ? 'alert alert-success' : 'alert alert-danger';
                messageBox.style.display = 'block';

                if (data.success) {
                    form.reset();
                }
            })
            .catch(error => {
                messageBox.textContent = "Erreur réseau, réessayez.";
                messageBox.className = 'alert alert-danger';
                messageBox.style.display = 'block';
            });
    });
});