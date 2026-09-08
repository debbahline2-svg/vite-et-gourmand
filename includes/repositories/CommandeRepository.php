<?php
class CommandeRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($userId, $idMenu, $dateEvenement, $nbPersonnes, $totalPrix, $distanceKm) {
        $sql = "INSERT INTO commandes (user_id, id_menu, date_evenement, nb_personnes, total_prix, statut, distance_km)
                VALUES (:uid, :id_m, :date_e, :nb, :total, 'En attente', :dist_km)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':uid'     => $userId,
            ':id_m'    => $idMenu,
            ':date_e'  => $dateEvenement,
            ':nb'      => $nbPersonnes,
            ':total'   => $totalPrix,
            ':dist_km' => $distanceKm
        ]);
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) FROM commandes";
        return $this->db->query($sql)->fetchColumn();
    }

    public function sumTotalPrix() {
        $sql = "SELECT SUM(total_prix) FROM commandes WHERE statut != 'annulee'";
        return $this->db->query($sql)->fetchColumn() ?: 0;
    }

    public function findAllWithDetails() {
        $sql = "SELECT c.*, u.nom as client_nom, u.prenom as client_prenom, m.titre as menu_titre 
                FROM commandes c 
                JOIN utilisateurs u ON c.user_id = u.id 
                JOIN menus m ON c.id_menu = m.id 
                ORDER BY c.date_commande DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}