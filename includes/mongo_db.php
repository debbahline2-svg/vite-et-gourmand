<?php
// On inclut l'autoloader généré par Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

class MongoDatabase {
    private $client;
    private $database;

    public function __construct() {
        // SÉCURITÉ EXAMEN : On vérifie si l'extension MongoDB est active sur la machine
        if (!class_exists('MongoDB\Driver\Manager')) {
            return;
        }

        if (getenv('APP_ENV') === 'production') {
            // Config Railway (en ligne) : host/port fournis par variables d'environnement
            $host = getenv('MONGO_HOST') ?: 'localhost';
            $port = getenv('MONGO_PORT') ?: '27017';
        } else {
            // Config locale (Docker) : nom du service défini dans docker-compose.yml
            $host = 'db-nosql';
            $port = '27017'; // port interne au réseau Docker (pas 27018, qui sert seulement à l'accès depuis l'hôte)
        }

        try {
            $this->client = new MongoDB\Client("mongodb://{$host}:{$port}");
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