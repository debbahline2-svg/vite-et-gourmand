<?php

require_once __DIR__ . '/../vendor/autoload.php';

class MongoDatabase {
    public $client;
    public $database;

    public function __construct() {
        if (getenv('APP_ENV') === 'production') {
            try {
                $mongoUrl = getenv('MONGO_URL');
                $this->client = new MongoDB\Client($mongoUrl);
                $this->database = $this->client->selectDatabase('vite_et_gourmand');
            } catch (Exception $e) {
                error_log("Erreur de connexion MongoDB : " . $e->getMessage());
            }
        } else {
            // Config locale (Docker)
            try {
                $password = getenv('MONGO_ROOT_PASSWORD') ?: 'changeme';
                $mongoUrl = "mongodb://root:" . $password . "@db-nosql:27017";
                $this->client = new MongoDB\Client($mongoUrl);
                $this->database = $this->client->selectDatabase('vite_et_gourmand');
            } catch (Exception $e) {
                error_log("Erreur de connexion MongoDB : " . $e->getMessage());
            }
        }
    }

    public function getCollection($name) {
        return $this->database->selectCollection($name);
    }
}