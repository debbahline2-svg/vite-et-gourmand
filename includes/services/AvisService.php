<?php
require_once __DIR__ . '/../repositories/AvisRepository.php';

class AvisService {
    private $repository;

    public function __construct($db) {
        $this->repository = new AvisRepository($db);
    }

    public function ajouterAvis($idCommande, $note, $commentaire) {
        // Règles métier / validations
        if (!$this->repository->commandeExists($idCommande)) {
            return ['success' => false, 'message' => 'Cette commande n\'existe pas.'];
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
}