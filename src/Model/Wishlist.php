<?php
require_once __DIR__ . '/connexionDB.php';

class Wishlist {
    private PDO $db;

    public function __construct() {
        $connexion = new ConnexionDB();
        $this->db = $connexion->getConnection();
    }

    // READ - Récupère la wishlist d'un étudiant
    public function getByEtudiant(int $etudiant_id): array {
        $stmt = $this->db->prepare(
            "SELECT offres.*, entreprises.nom AS entreprise_nom
             FROM wishlist
             JOIN offres ON wishlist.offre_id = offres.id
             JOIN entreprises ON offres.entreprise_id = entreprises.id
             WHERE wishlist.etudiant_id = :etudiant_id
             ORDER BY offres.titre ASC"
        );
        $stmt->execute([':etudiant_id' => $etudiant_id]);
        return $stmt->fetchAll();
    }

    // CREATE - Ajoute une offre à la wishlist
    public function add(int $etudiant_id, int $offre_id): bool {
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO wishlist (etudiant_id, offre_id)
             VALUES (:etudiant_id, :offre_id)"
        );
        return $stmt->execute([
            ':etudiant_id' => $etudiant_id,
            ':offre_id'    => $offre_id
        ]);
    }

    // DELETE - Retire une offre de la wishlist
    public function remove(int $etudiant_id, int $offre_id): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM wishlist 
             WHERE etudiant_id = :etudiant_id 
             AND offre_id = :offre_id"
        );
        return $stmt->execute([
            ':etudiant_id' => $etudiant_id,
            ':offre_id'    => $offre_id
        ]);
    }

    // READ - Vérifie si une offre est déjà dans la wishlist
    public function isInWishlist(int $etudiant_id, int $offre_id): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM wishlist 
             WHERE etudiant_id = :etudiant_id 
             AND offre_id = :offre_id"
        );
        $stmt->execute([
            ':etudiant_id' => $etudiant_id,
            ':offre_id'    => $offre_id
        ]);
        return $stmt->fetchColumn() > 0;
    }
}
