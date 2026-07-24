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
}