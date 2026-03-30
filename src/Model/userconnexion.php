<?php

require_once __DIR__ . '/connexionDB.php';

class UserConnexion {
    private PDO $db;

    public function __construct() {
        // On crée la connexion à la BDD
        $connexion = new ConnexionDB();
        $this->db = $connexion->getConnection();
    }

    public function login(string $email, string $password): array|false {
        // On cherche l'utilisateur par son email dans la BDD
        // On utilise une requête préparée pour éviter les injections SQL
        // C'est quoi une injection SQL ? Si quelqu'un tape "' OR 1=1" dans
        // le champ email, sans protection il pourrait accéder à tous les comptes.
        // Les requêtes préparées empêchent ça.
        $stmt = $this->db->prepare(
            "SELECT id, nom, prenom, email, password, role 
             FROM utilisateurs 
             WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        // Si l'utilisateur n'existe pas, on retourne false
        if (!$user) {
            return false;
        }

        // password_verify compare le mot de passe tapé avec
        // le hash stocké en BDD. On ne peut pas comparer directement
        // car le mot de passe en BDD est chiffré.
        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Si tout est bon on retourne les infos de l'utilisateur
        return $user;
    }
}
