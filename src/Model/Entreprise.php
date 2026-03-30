<?php
require_once __DIR__ . '/connexionDB.php';

class Entreprise {
    private PDO $db;

    public function __construct() {
        $connexion = new ConnexionDB();
        $this->db = $connexion->getConnection();
    }

    // READ - Récupère toutes les entreprises
    // COUNT(candidatures.id) = nombre de stagiaires ayant postulé
    // AVG(evaluations.note) = moyenne des évaluations
    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT entreprises.*,
                COUNT(DISTINCT candidatures.id) AS nb_candidatures,
                ROUND(AVG(evaluations.note), 1) AS moyenne_notes
             FROM entreprises
             LEFT JOIN offres ON offres.entreprise_id = entreprises.id
             LEFT JOIN candidatures ON candidatures.offre_id = offres.id
             LEFT JOIN evaluations ON evaluations.entreprise_id = entreprises.id
             GROUP BY entreprises.id
             ORDER BY entreprises.nom ASC"
        );
        return $stmt->fetchAll();
    }

    // READ - Récupère une seule entreprise par son id
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT entreprises.*,
                COUNT(DISTINCT candidatures.id) AS nb_candidatures,
                ROUND(AVG(evaluations.note), 1) AS moyenne_notes
             FROM entreprises
             LEFT JOIN offres ON offres.entreprise_id = entreprises.id
             LEFT JOIN candidatures ON candidatures.offre_id = offres.id
             LEFT JOIN evaluations ON evaluations.entreprise_id = entreprises.id
             WHERE entreprises.id = :id
             GROUP BY entreprises.id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // READ - Recherche une entreprise par nom
    public function search(string $keyword): array {
        $keyword = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            "SELECT entreprises.*,
                COUNT(DISTINCT candidatures.id) AS nb_candidatures,
                ROUND(AVG(evaluations.note), 1) AS moyenne_notes
             FROM entreprises
             LEFT JOIN offres ON offres.entreprise_id = entreprises.id
             LEFT JOIN candidatures ON candidatures.offre_id = offres.id
             LEFT JOIN evaluations ON evaluations.entreprise_id = entreprises.id
             WHERE entreprises.nom LIKE :keyword
             OR entreprises.description LIKE :keyword
             GROUP BY entreprises.id"
        );
        $stmt->execute([':keyword' => $keyword]);
        return $stmt->fetchAll();
    }

    // CREATE - Crée une nouvelle entreprise
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO entreprises (nom, description, email, telephone)
             VALUES (:nom, :description, :email, :telephone)"
        );
        return $stmt->execute([
            ':nom'         => $data['nom'],
            ':description' => $data['description'],
            ':email'       => $data['email'],
            ':telephone'   => $data['telephone']
        ]);
    }

    // UPDATE - Modifie une entreprise
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            "UPDATE entreprises
             SET nom = :nom, description = :description,
                 email = :email, telephone = :telephone
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id'          => $id,
            ':nom'         => $data['nom'],
            ':description' => $data['description'],
            ':email'       => $data['email'],
            ':telephone'   => $data['telephone']
        ]);
    }

    // DELETE - Supprime une entreprise
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM entreprises WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // CREATE - Ajoute une évaluation
    public function evaluate(int $entreprise_id, int $auteur_id, int $note, string $commentaire): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO evaluations (entreprise_id, auteur_id, note, commentaire)
             VALUES (:entreprise_id, :auteur_id, :note, :commentaire)"
        );
        return $stmt->execute([
            ':entreprise_id' => $entreprise_id,
            ':auteur_id'     => $auteur_id,
            ':note'          => $note,
            ':commentaire'   => $commentaire
        ]);
    }

    // READ - Récupère toutes les évaluations d'une entreprise
    public function getEvaluations(int $entreprise_id): array {
        $stmt = $this->db->prepare(
            "SELECT evaluations.*, 
                utilisateurs.nom, utilisateurs.prenom
             FROM evaluations
             JOIN utilisateurs ON evaluations.auteur_id = utilisateurs.id
             WHERE evaluations.entreprise_id = :id
             ORDER BY evaluations.created_at DESC"
        );
        $stmt->execute([':id' => $entreprise_id]);
        return $stmt->fetchAll();
    }
}
