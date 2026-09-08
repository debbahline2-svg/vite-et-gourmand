<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn; // public car on le récupère de l'extérieur via getConnection()
 
    public function __construct(){
        // On distingue 2 environnements : production (Railway) vs local (Docker)
        // Ça évite d'avoir des identifiants de connexion en dur dans le code
        if (getenv('APP_ENV') === 'production') {
            // Config Railway (en ligne)
             // En prod, les identifiants viennent des variables d'environnement
            // définies sur la plateforme d'hébergement (Railway) — jamais dans le code source
            $this->host = getenv('MYSQLHOST');
            $this->db_name = getenv('MYSQLDATABASE');
            $this->username = getenv('MYSQLUSER');
            $this->password = getenv('MYSQLPASSWORD'); // Récupéré de façon sécurisée depuis les variables Railway
            $this->port = getenv('MYSQLPORT');
        } else {
            // Config locale (Docker)
             // En local, on utilise le nom du service Docker Compose ('db-sql')
            // Docker résout ce nom vers l'IP interne du conteneur MySQL
            $this->host = 'db-sql';
            $this->db_name = 'vite_et_gourmand';
            $this->username = 'root';
            $this->password = getenv('MYSQL_ROOT_PASSWORD') ?: 'changeme';
            $this->port = '3306';
        }
    }
 
    public function getConnection() {
        $this->conn = null;
        try {
             // DSN = Data Source Name, la "carte d'identité" de la connexion pour PDO
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
             // ATTR_ERRMODE::EXCEPTION = si une requête SQL échoue, PDO lève une Exception
            // au lieu de juste renvoyer 'false' silencieusement (comportement par défaut).
            // Ça permet d'attraper les erreurs proprement avec try/catch ailleurs dans le code.
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Erreur loguée côté serveur (jamais affichée à l'écran), pour ne pas exposer
            // de détails techniques (host, structure de la base) à l'utilisateur final.
            error_log("Erreur de connexion à la base de données : " . $exception->getMessage());
            // On arrête immédiatement l'exécution : sans ça, le reste du code continuerait
            // avec une connexion null et provoquerait une fatal error non contrôlée,
            // qui afficherait elle-même des chemins serveur bruts (pire fuite d'info).
            die("Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer plus tard.");
        }
        return $this->conn;
    }
}
?>
