<?php
require_once __DIR__ . '/connexionDB.php';

class Evaluation {
    private PDO $db;

    public function __construct() {
        $connexion = new ConnexionDB();
        $this->db = $connexion->getConnection();
    }

    // CREATE - Ajoute une évaluation pour une entreprise
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO evaluations (entreprise_id, auteur_id, note, commentaire)
             VALUES (:entreprise_id, :auteur_id, :note, :commentaire)"
        );
        return $stmt->execute([
            ':entreprise_id' => $data['entreprise_id'],
            ':auteur_id'     => $data['auteur_id'],
            ':note'          => $data['note'],
            ':commentaire'   => $data['commentaire']
        ]);
    }

    // READ - Récupère toutes les évaluations d'une entreprise
    public function getByEntreprise(int $entreprise_id): array {
        $stmt = $this->db->prepare(
            "SELECT evaluations.*,
                utilisateurs.nom AS auteur_nom,
                utilisateurs.prenom AS auteur_prenom
             FROM evaluations
             JOIN utilisateurs ON evaluations.auteur_id = utilisateurs.id
             WHERE evaluations.entreprise_id = :entreprise_id
             ORDER BY evaluations.created_at DESC"
        );
        $stmt->execute([':entreprise_id' => $entreprise_id]);
        return $stmt->fetchAll();
    }

    // READ - Calcule la moyenne des notes d'une entreprise
    public function getMoyenne(int $entreprise_id): float {
        $stmt = $this->db->prepare(
            "SELECT ROUND(AVG(note), 1) AS moyenne
             FROM evaluations
             WHERE entreprise_id = :entreprise_id"
        );
        $stmt->execute([':entreprise_id' => $entreprise_id]);
        $result = $stmt->fetch();
        return $result['moyenne'] ?? 0;
    }
}
