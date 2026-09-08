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

    // NOUVELLE MÉTHODE : vérifie que la commande appartient bien à l'utilisateur ET qu'elle est livrée
    public function commandeAppartientAUserEtLivree($idCommande, $idUser) {
        $sql = "SELECT COUNT(*) FROM commandes 
                WHERE id = ? AND user_id = ? AND LOWER(TRIM(statut)) IN ('livré', 'livre')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCommande, $idUser]);
        return $stmt->fetchColumn() > 0;
    }

    public function avisExists($idAvis) {
        $sql = "SELECT COUNT(*) FROM avis WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idAvis]);
        return $stmt->fetchColumn() > 0;
    }

    public function avisAppartientAUser($idAvis, $idUser) {
        $sql = "SELECT COUNT(*) FROM avis a
                INNER JOIN commandes c ON a.id_commande = c.id
                WHERE a.id = ? AND c.user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idAvis, $idUser]);
        return $stmt->fetchColumn() > 0;
    }

    public function delete($idAvis) {
        $sql = "DELETE FROM avis WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idAvis]);
    }

    public function findByCommande($idCommande) {
        $sql = "SELECT * FROM avis WHERE id_commande = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCommande]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}