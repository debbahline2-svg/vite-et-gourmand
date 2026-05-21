<?php

require_once '../includes/header.php';


$message = "";

$erreur = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération et nettoyage des données de base

    $titre = htmlspecialchars(trim($_POST['titre'] ?? ''));

    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));

    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

    $formule = htmlspecialchars(trim($_POST['formule'] ?? ''));

    $convives = intval($_POST['convives'] ?? 0);

    $date_evenement = htmlspecialchars(trim($_POST['date_evenement'] ?? ''));

    $description = htmlspecialchars(trim($_POST['description'] ?? ''));


    if (!$email || empty($titre) || empty($description)) {

        $erreur = "Veuillez remplir tous les champs obligatoires (*) avec des informations valides.";

    } else {

        // Destination : Julie et José (Vite & Gourmand)

        $to = "contact@viteetgourmand.fr";

        $subject = "[Contact Site] - " . $titre;

       

        // Construction du corps du mail

        $body = "Nouvelle demande de contact reçue depuis le site internet :\n\n";

        $body .= "Nom complet : " . $nom . "\n";

        $body .= "Email du demandeur : " . $email . "\n";

        $body .= "Formule sélectionnée : " . $formule . "\n";

        $body .= "Nombre de convives : " . ($convives > 0 ? $convives : "Non spécifié") . "\n";

        $body .= "Date de l'événement : " . (!empty($date_evenement) ? $date_evenement : "Non spécifiée") . "\n\n";


        // Si c'est une prestation sur-mesure, on ajoute les options spécifiques

        if ($formule === "Autre prestation sur-mesure") {

            $format = htmlspecialchars(trim($_POST['format_repas'] ?? ''));

            $budget = htmlspecialchars(trim($_POST['budget'] ?? ''));

            $option_culinaire = htmlspecialchars(trim($_POST['option_culinaire'] ?? ''));

           

            $body .= "--- OPTIONS SUR-MESURE ---\n";

            $body .= "Format du repas : " . $format . "\n";

            $body .= "Option culinaire : " . $option_culinaire . "\n";

            $body .= "Budget global estimé : " . (!empty($budget) ? $budget . " €" : "Non spécifié") . "\n";

           

            $prestations = [];

            if (isset($_POST['presta_vins'])) $prestations[] = "Accords Mets & Vins";

            if (isset($_POST['presta_service'])) $prestations[] = "Maîtres d'hôtel / Service";

            if (isset($_POST['presta_vaisselle'])) $prestations[] = "Location de vaisselle & nappage";

           

            $body .= "Prestations complémentaires : " . (!empty($prestations) ? implode(', ', $prestations) : "Aucune") . "\n";

            $body .= "---------------------------\n\n";

        }


        $body .= "Description du projet :\n" . $description . "\n";

       

        // Headers du mail

        $headers = "From: " . $email . "\r\n";

        $headers .= "Reply-To: " . $email . "\r\n";

        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";


        if (@mail($to, $subject, $body, $headers)) {

            $message = "Votre demande a bien été transmise à notre équipe. Nous reviendrons vers vous rapidement.";

        } else {

            $message = "Votre demande a été prise en compte avec succès (Simulation d'envoi d'e-mail).";

        }

    }

}

?>


<style>

    body { background-color: #0c0b0a !important; color: white; font-family: 'Roboto', sans-serif; }

    .contact-card { background: #161513; border: 1px solid rgba(193, 127, 58, 0.3); border-radius: 20px; padding: 40px; width: 100%; max-width: 900px; margin: 50px auto; }

    .form-label { color: #C17F3A; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }

    .form-control, .form-select { background-color: transparent !important; border: none !important; border-bottom: 1px solid #333 !important; border-radius: 0 !important; color: white !important; padding-left: 0; padding-right: 0; transition: 0.3s; }

    .form-control:focus, .form-select:focus { box-shadow: none !important; border-bottom: 1px solid #C17F3A !important; }

    .form-select option { background-color: #161513; color: white; }

   

    .sur-mesure-box { border: 1px dashed #C17F3A; border-radius: 10px; padding: 25px; margin-top: 25px; margin-bottom: 25px; display: none; background: rgba(193, 127, 58, 0.02); }

    .sur-mesure-title { color: #C17F3A; font-family: 'Playfair Display', serif; font-size: 1.2rem; margin-bottom: 20px; font-style: italic; }

   

    .form-check-input { background-color: transparent; border: 1px solid #C17F3A; }

    .form-check-input:checked { background-color: #C17F3A; border-color: #C17F3A; }

    .form-check-label { color: #fff; font-size: 0.9rem; margin-left: 5px; cursor: pointer; }


    .btn-gold { background: #C17F3A; color: black; font-weight: 700; border: none; padding: 14px 40px; border-radius: 30px; letter-spacing: 1px; transition: 0.3s; }

    .btn-gold:hover { background: #e6a75a; transform: translateY(-2px); cursor: pointer; }

   

    ::placeholder { color: #444 !important; font-style: italic; }

</style>


<div class="container">

    <div class="contact-card shadow-lg">

        <h1 class="text-center mb-5" style="font-family: 'Playfair Display', serif; font-weight: 700;">

            <span style="color: #C17F3A;">Contactez</span> l'Équipe

        </h1>


        <?php if($message): ?>

            <div class="alert alert-success text-center border-0 p-4 mb-0" style="background: rgba(193, 127, 58, 0.1); color: #C17F3A; border-radius: 15px;">

                <h4 class="alert-heading mb-2">Merci !</h4>

                <p class="mb-0"><?= $message ?></p>

            </div>

        <?php else: ?>


            <?php if($erreur): ?>

                <div class="alert alert-danger text-center border-0 small mb-4" style="background: rgba(255,0,0,0.1); color: #ff6b6b;"><?= $erreur ?></div>

            <?php endif; ?>


            <form method="POST" id="contactForm">

                <div class="mb-4">

                    <label class="form-label">Titre de votre demande *</label>

                    <input type="text" name="titre" class="form-control" placeholder="Ex: Devis Buffet Anniversaire Sur-mesure" required>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label">Nom Complet</label>

                        <input type="text" name="nom" class="form-control" placeholder="M. ou Mme...">

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label">Votre Email *</label>

                        <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" required>

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label">Formule souhaitée</label>

                        <select name="formule" id="formuleSelect" class="form-select">

                            <option value="Menu Traditionnel">Menu Traditionnel</option>

                            <option value="Formule Événementielle (Noël, Pâques)">Formule Événementielle (Noël, Pâques)</option>

                            <option value="Autre prestation sur-mesure" selected>✨ Autre prestation sur-mesure</option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label">Nombre de convives</label>

                        <input type="number" name="convives" class="form-control" placeholder="Ex: 50">

                    </div>

                </div>


                <div class="mb-4">

                    <label class="form-label">Date souhaitée de l'événement</label>

                    <input type="date" name="date_evenement" class="form-control">

                </div>


                <div class="sur-mesure-box" id="surMesureBox">

                    <div class="sur-mesure-title">Options de votre Prestation Sur-mesure</div>

                   

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <label class="form-label">Format du repas</label>

                            <select name="format_repas" class="form-select">

                                <option value="Buffet dînatoire / debout">Buffet dînatoire / debout</option>

                                <option value="Repas assis servi à l'assiette">Repas assis servi à l'assiette</option>

                                <option value="Cocktail apéritif">Cocktail apéritif</option>

                            </select>

                        </div>

                       

                        <div class="col-md-6 mb-4">

                            <label class="form-label">Options culinaires</label>

                            <select name="option_culinaire" class="form-select">

                                <option value="Aucune contrainte">Aucune contrainte particulière</option>

                                <option value="Végétarien">Végétarien</option>

                                <option value="Sans Gluten">Sans Gluten</option>

                                <option value="Halal">Halal</option>

                                <option value="Sans Porc">Sans Porc</option>

                                <option value="Allergies particulières">Allergies particulières (à préciser en description)</option>

                            </select>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-12 mb-4">

                            <label class="form-label">Budget global estimé (€)</label>

                            <input type="number" name="budget" class="form-control" placeholder="Ex: 1500">

                        </div>

                    </div>


                    <div class="mb-2">

                        <label class="form-label d-block">Prestations complémentaires souhaitées :</label>

                        <div class="d-flex flex-wrap gap-4 mt-2">

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" name="presta_vins" id="vins">

                                <label class="form-check-label" for="vins">Accords Mets & Vins</label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" name="presta_service" id="service">

                                <label class="form-check-label" for="service">Maîtres d'hôtel / Service</label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input" type="checkbox" name="presta_vaisselle" id="vaisselle">

                                <label class="form-check-label" for="vaisselle">Location de vaisselle & nappage</label>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="mb-5">

                    <label class="form-label">Description de votre projet *</label>

                    <textarea name="description" class="form-control" rows="4" placeholder="Détaillez vos attentes (contraintes, thématique, allergies...)" required></textarea>

                </div>


                <div class="text-center">

                    <button type="submit" class="btn btn-gold text-uppercase">Envoyer ma demande</button>

                </div>

            </form>

        <?php endif; ?>

    </div>

</div>


<script>

    document.addEventListener('DOMContentLoaded', function() {

        const formuleSelect = document.getElementById('formuleSelect');

        const surMesureBox = document.getElementById('surMesureBox');


        function toggleSurMesure() {

            if (formuleSelect.value === 'Autre prestation sur-mesure') {

                surMesureBox.style.display = 'block';

            } else {

                surMesureBox.style.display = 'none';

            }

        }


        formuleSelect.addEventListener('change', toggleSurMesure);

        toggleSurMesure();

    });

</script>


<?php require_once '../includes/footer.php'; ?> 