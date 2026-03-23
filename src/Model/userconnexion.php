<?php
include_once 'connexionDB.php';


class UserConnexion {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM Utilisateurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mdp'])) {
            return true; 
        } else {
            return false;
        }
    }


    public function getuserrole($email) {
        $stmt = $this->db->prepare("SELECT role FROM Utilisateurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['role'] : null;
    }

    public function getuserprenom($email) {
        $stmt = $this->db->prepare("SELECT prenom FROM Utilisateurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['prenom'] : null;
    }

     public function register($email, $password, $role, $nom, $prenom) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO Utilisateurs (email, mdp, role, nom, prenom) VALUES (:email, :mdp, :role, :nom, :prenom)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdp', $hashedPassword);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        return $stmt->execute();
    }
}