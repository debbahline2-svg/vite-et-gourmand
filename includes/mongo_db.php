<?php
// On inclut l'autoloader généré par Composer s'il existe
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

class MongoDatabase {
    private $client;
    private $database;

    public function __construct() {
        // SÉCURITÉ EXAMEN : On vérifie si l'extension MongoDB est active sur la machine
        if (!class_exists('MongoDB\Driver\Manager')) {
            // Si elle n'est pas chargée dans XAMPP, on s'arrête gentiment sans faire planter le site
            return;
        }

        try {
            // Connexion locale au serveur MongoDB (Port 27017)
            $this->client = new MongoDB\Client("mongodb://localhost:27017");
            $this->database = $this->client->vite_et_gourmand;
        } catch (Exception $e) {
            error_log("Erreur de connexion MongoDB : " . $e->getMessage());
        }
    }

    public function getCollection($collectionName) {
        if ($this->database) {
            return $this->database->$collectionName;
        }
        return null;
    }
}
?>