<?php
class AvisRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($idCommande, $note, $commentaire) {
        $sql = "INSERT INTO avis (id_commande, note, commentaire) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idCommande, $note, $commentaire]);
    }

    public function commandeExists($idCommande) {
        $sql = "SELECT COUNT(*) FROM commandes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCommande]);
        return $stmt->fetchColumn() > 0;
    }
}