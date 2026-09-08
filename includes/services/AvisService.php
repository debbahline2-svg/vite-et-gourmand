<?php
require_once __DIR__ . '/../repositories/AvisRepository.php';

class AvisService {
    private $repository;

    public function __construct($db) {
        $this->repository = new AvisRepository($db);
    }

    public function ajouterAvis($idCommande, $idUser, $note, $commentaire) {
        // Règles métier / validations

        if (!$this->repository->commandeExists($idCommande)) {
            return ['success' => false, 'message' => 'Cette commande n\'existe pas.'];
        }

        // Vérifie que la commande appartient à l'utilisateur connecté ET qu'elle est livrée
        if (!$this->repository->commandeAppartientAUserEtLivree($idCommande, $idUser)) {
            return ['success' => false, 'message' => 'Vous ne pouvez laisser un avis que sur vos propres commandes livrées.'];
        }

        if ($this->repository->findByCommande($idCommande)) {
            return ['success' => false, 'message' => 'Un avis existe déjà pour cette commande.'];
        }

        if ($note < 1 || $note > 5) {
            return ['success' => false, 'message' => 'La note doit être comprise entre 1 et 5.'];
        }

        if (empty(trim($commentaire))) {
            return ['success' => false, 'message' => 'Le commentaire ne peut pas être vide.'];
        }

        $success = $this->repository->create($idCommande, $note, $commentaire);

        return $success
            ? ['success' => true, 'message' => 'Merci pour votre avis !']
            : ['success' => false, 'message' => 'Une erreur est survenue.'];
    }

    public function supprimerAvis($idAvis, $idUser, $role) {
        if (empty($idAvis) || !is_numeric($idAvis)) {
            return ['success' => false, 'message' => 'Identifiant invalide.'];
        }

        if (!$this->repository->avisExists($idAvis)) {
            return ['success' => false, 'message' => 'Cet avis n\'existe pas.'];
        }

        $estAutorise = ($role === 'admin') || $this->repository->avisAppartientAUser($idAvis, $idUser);

        if (!$estAutorise) {
            return ['success' => false, 'message' => 'Vous n\'êtes pas autorisé à supprimer cet avis.'];
        }

        $success = $this->repository->delete($idAvis);

        return $success
            ? ['success' => true, 'message' => 'Avis supprimé avec succès.']
            : ['success' => false, 'message' => 'Une erreur est survenue.'];
    }
}