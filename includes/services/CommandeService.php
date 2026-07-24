<?php
require_once __DIR__ . '/../repositories/CommandeRepository.php';

class CommandeService {
    private $repository;

    public function __construct($db) {
        $this->repository = new CommandeRepository($db);
    }

    public function calculerTotal($prixUnitaire, $nbPersonnes, $nbMinPersonnes, $ville, $distance) {
        $prixTotalMenu = $prixUnitaire * $nbPersonnes;

        if ($nbPersonnes >= ($nbMinPersonnes + 5)) {
            $prixTotalMenu *= 0.90;
        }

        $fraisLivraison = (strtolower($ville) === 'bordeaux') ? 5.00 : ($distance * 5.59);

        return $prixTotalMenu + $fraisLivraison;
    }

    public function creerCommande($userId, $idMenu, $dateEvenement, $nbPersonnes, $totalPrix, $distance) {
        try {
            $success = $this->repository->create($userId, $idMenu, $dateEvenement, $nbPersonnes, $totalPrix, $distance);
            return ['success' => $success, 'message' => 'Félicitations ! Votre commande a été transmise. Montant final : ' . number_format($totalPrix, 2, ',', ' ') . ' €'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de base de données : ' . $e->getMessage()];
        }
    }
}