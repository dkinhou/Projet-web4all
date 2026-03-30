<?php
require_once __DIR__ . '/connexionDB.php';

class Offre {
    private PDO $db;

    public function __construct() {
        // On ouvre la connexion à la BDD
        $connexion = new ConnexionDB();
        $this->db = $connexion->getConnection();
    }

    // READ - Récupère toutes les offres avec le nom de l'entreprise
    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT offres.*, entreprises.nom AS entreprise_nom 
             FROM offres 
             JOIN entreprises ON offres.entreprise_id = entreprises.id
             ORDER BY offres.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    // READ - Récupère une seule offre par son id
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT offres.*, entreprises.nom AS entreprise_nom 
             FROM offres 
             JOIN entreprises ON offres.entreprise_id = entreprises.id
             WHERE offres.id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // READ - Recherche des offres par mot clé
    public function search(string $keyword): array {
        $keyword = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            "SELECT offres.*, entreprises.nom AS entreprise_nom 
             FROM offres 
             JOIN entreprises ON offres.entreprise_id = entreprises.id
             WHERE offres.titre LIKE :keyword 
             OR offres.description LIKE :keyword
             ORDER BY offres.created_at DESC"
        );
        $stmt->execute([':keyword' => $keyword]);
        return $stmt->fetchAll();
    }

    // CREATE - Crée une nouvelle offre
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO offres (titre, description, remuneration, date_offre, entreprise_id) 
             VALUES (:titre, :description, :remuneration, :date_offre, :entreprise_id)"
        );
        return $stmt->execute([
            ':titre'        => $data['titre'],
            ':description'  => $data['description'],
            ':remuneration' => $data['remuneration'],
            ':date_offre'   => $data['date_offre'],
            ':entreprise_id'=> $data['entreprise_id']
        ]);
    }

    // UPDATE - Modifie une offre existante
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE offres 
             SET titre = :titre, description = :description, 
                 remuneration = :remuneration, date_offre = :date_offre,
                 entreprise_id = :entreprise_id
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'           => $id,
            ':titre'        => $data['titre'],
            ':description'  => $data['description'],
            ':remuneration' => $data['remuneration'],
            ':date_offre'   => $data['date_offre'],
            ':entreprise_id'=> $data['entreprise_id']
        ]);
    }

    // DELETE - Supprime une offre
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM offres WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // READ - Compte le nombre total d'offres
    public function count(): int {
        return $this->db->query("SELECT COUNT(*) FROM offres")->fetchColumn();
    }
}
