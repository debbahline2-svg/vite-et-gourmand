<?php
class User {
    private $conn;
    private $table_name = "utilisateurs";

    public $id;
    public $nom;
    public $prenom;
    public $email; 
    public $gsm;
    public $adresse;
    public $password;
    public $role;
    public $is_active; // Ajouté pour la sécurité

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- INSCRIPTION ---
    public function register() {
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        $query = "INSERT INTO " . $this->table_name . " 
                (identifiant, mot_de_passe, role, nom, prenom, gsm, adresse, is_active) 
                VALUES (:email, :password, :role, :nom, :prenom, :gsm, :adresse, 1)";

        $stmt = $this->conn->prepare($query);

        if(empty($this->role)) { $this->role = "client"; }

        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $password_hash);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":gsm", $this->gsm);
        $stmt->bindParam(":adresse", $this->adresse);

        return $stmt->execute();
    }

    // --- CONNEXION (SÉCURISÉE EVIDEMMENT) ---
    public function login() {
        // La condition "AND is_active = 1" empêche les comptes désactivés de se connecter
        $query = "SELECT * FROM " . $this->table_name . " WHERE identifiant = :email AND is_active = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($this->password, $row['mot_de_passe'])) {
                return $row;
            }
        }
        return false;
    }

    // --- RÉCUPÉRER UN UTILISATEUR ---
    public function getOneById($id) {
        $query = "SELECT id, nom, prenom, identifiant, gsm, adresse, role, is_active 
                  FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- MISE À JOUR DU PROFIL ---
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nom = :nom, prenom = :prenom, gsm = :gsm, adresse = :adresse 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':gsm', $this->gsm);
        $stmt->bindParam(':adresse', $this->adresse);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}
?>