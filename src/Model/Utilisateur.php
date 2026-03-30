<?php
require_once __DIR__ . '/connexionDB.php';

class Utilisateur {
    private PDO $db;

    public function __construct() {
        $connexion = new ConnexionDB();
        $this->db = $connexion->getConnection();
    }

    // READ - Récupère tous les utilisateurs
    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT id, nom, prenom, email, role, created_at 
             FROM utilisateurs 
             ORDER BY nom ASC"
        );
        return $stmt->fetchAll();
    }

    // READ - Récupère tous les utilisateurs par rôle
    // Ex: getByRole('pilote') retourne tous les pilotes
    public function getByRole(string $role): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, prenom, email, role, created_at 
             FROM utilisateurs 
             WHERE role = :role
             ORDER BY nom ASC"
        );
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll();
    }

    // READ - Récupère un seul utilisateur par son id
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT id, nom, prenom, email, role, created_at 
             FROM utilisateurs 
             WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // READ - Recherche un utilisateur par nom ou prénom
    public function search(string $keyword, string $role = ''): array {
        $keyword = '%' . $keyword . '%';

        if ($role) {
            $stmt = $this->db->prepare(
                "SELECT id, nom, prenom, email, role, created_at 
                 FROM utilisateurs 
                 WHERE (nom LIKE :keyword OR prenom LIKE :keyword)
                 AND role = :role
                 ORDER BY nom ASC"
            );
            $stmt->execute([':keyword' => $keyword, ':role' => $role]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT id, nom, prenom, email, role, created_at 
                 FROM utilisateurs 
                 WHERE nom LIKE :keyword OR prenom LIKE :keyword
                 ORDER BY nom ASC"
            );
            $stmt->execute([':keyword' => $keyword]);
        }

        return $stmt->fetchAll();
    }

    // CREATE - Crée un nouveau compte
    // PASSWORD_BCRYPT chiffre le mot de passe avant de le stocker
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO utilisateurs (nom, prenom, email, password, role)
             VALUES (:nom, :prenom, :email, :password, :role)"
        );
        return $stmt->execute([
            ':nom'      => $data['nom'],
            ':prenom'   => $data['prenom'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':role'     => $data['role']
        ]);
    }

    // UPDATE - Modifie un compte
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE utilisateurs
             SET nom = :nom, prenom = :prenom, email = :email
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'     => $id,
            ':nom'    => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email'  => $data['email']
        ]);
    }

    // DELETE - Supprime un compte
    public function delete(int $id): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM utilisateurs WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}
