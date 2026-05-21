<?php
class Database {
private $host = 'kodama.proxy.rlwy.net';
private $db_name = 'railway';
private $username = 'root';
private $password = 'jAwHSNIFOwqlphobkQMMmbRUthjqRqve';
private $port = '41233';
public $conn;

public function getConnection() {
$this->conn = null;
try {
$this->conn = new PDO(
"mysql:host=" . $this->host .
";port=" . $this->port .
";dbname=" . $this->db_name,
$this->username,
$this->password
);
$this->conn->exec("set names utf8");
} catch(PDOException $e) {
die("Erreur de connexion : " . $e->getMessage());
}
return $this->conn;
}
}
?>