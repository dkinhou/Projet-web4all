<?php

class ConnexionDB {
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private ?PDO $conn = null;

    public function __construct() {

        // Étape 1 : on cherche le fichier .env à la racine du projet
        $envFile = __DIR__ . '/../../.env';

        // Étape 2 : on lit chaque ligne du .env et on la met en mémoire
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                [$key, $value] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }

        // Étape 3 : on récupère les 4 informations de connexion
        $this->host     = $_ENV['DB_HOST'] ?? 'localhost';
        $this->dbname   = $_ENV['DB_NAME'] ?? '';
        $this->username = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';

        try {
            // Étape 4 : on se connecte à MySQL via PDO
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Étape 5 : si ça échoue on affiche l'erreur
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    // Les autres fichiers PHP appellent cette méthode pour obtenir la connexion
    public function getConnection(): PDO {
        return $this->conn;
    }
}
