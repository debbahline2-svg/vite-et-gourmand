document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.avisForm');

    forms.forEach(form => {
        const messageBox = form.nextElementSibling;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const idCommande = form.dataset.idCommande;
            const formData = new FormData(form);

            fetch(`api/submit_avis.php?id_commande=${idCommande}`, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    messageBox.textContent = data.message;
                    messageBox.className = data.success ? 'avisMessage alert alert-success' : 'avisMessage alert alert-danger';
                    messageBox.style.display = 'block';

                    if (data.success) {
                        form.reset();
                        form.style.display = 'none';
                    }
                })
                .catch(error => {
                    messageBox.textContent = "Erreur réseau, réessayez.";
                    messageBox.className = 'avisMessage alert alert-danger';
                    messageBox.style.display = 'block';
                });
        });
    });

    // --- Suppression d'un avis ---
    const deleteButtons = document.querySelectorAll('.deleteAvisBtn');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Voulez-vous vraiment supprimer votre avis ?')) {
                return;
            }

            const idAvis = btn.dataset.idAvis;
            const avisContainer = btn.closest('.avisExistant');
            const messageBox = avisContainer.nextElementSibling;

            fetch('api/supprimer_avis.php', {
                method: 'POST',
                body: new URLSearchParams({ id: idAvis })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        avisContainer.remove();
                        location.reload();
                    } else {
                        messageBox.textContent = data.message;
                        messageBox.className = 'avisMessage alert alert-danger';
                        messageBox.style.display = 'block';
                    }
                })
                .catch(error => {
                    messageBox.textContent = "Erreur réseau, réessayez.";
                    messageBox.className = 'avisMessage alert alert-danger';
                    messageBox.style.display = 'block';
                });
        });
    });
});