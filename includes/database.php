<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct() {
        if (getenv('APP_ENV') === 'production') {
            // Config Railway (en ligne)
            $this->host     = 'kodama.proxy.rlwy.net';
            $this->db_name  = 'railway';
            $this->username = 'root';
            $this->password = 'jAwHSNIFOwqlphobkQNWmbRUthjqRqve';
            $this->port     = '41233';
        } else {
            // Config locale (Docker)
            $this->host     = 'db-sql';
            $this->db_name  = 'vite_et_gourmand';
            $this->username = 'root';
            $this->password = 'changeme';
            $this->port     = '3306';
        }
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $e) {
            echo "Erreur connexion : " . $e->getMessage();
        }
        return $this->conn;
    }
}