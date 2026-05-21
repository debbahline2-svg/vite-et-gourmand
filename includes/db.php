<?php
class Database {
private $host;
private $db_name;
private $username;
private $password;
private $port;
public $conn;

public function getConnection() {
$this->host = getenv('MYSQLHOST');
$this->db_name = getenv('MYSQLDATABASE');
$this->username = getenv('MYSQLUSER');
$this->password = getenv('MYSQLPASSWORD');
$this->port = getenv('MYSQLPORT');

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
