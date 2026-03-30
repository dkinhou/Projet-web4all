<?php
require_once __DIR__ . '/connexionDB.php';

class Candidature {
    private PDO $db;

    public function __construct() {
        $connexion = new ConnexionDB();
        $this->db = $connexion->getConnection();
    }

    // CREATE - L'étudiant postule à une offre
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO candidatures (etudiant_id, offre_id, lettre_motivation, cv_path)
             VALUES (:etudiant_id, :offre_id, :lettre_motivation, :cv_path)"
        );
        return $stmt->execute([
            ':etudiant_id'      => $data['etudiant_id'],
            ':offre_id'         => $data['offre_id'],
            ':lettre_motivation'=> $data['lettre_motivation'],
            ':cv_path'          => $data['cv_path']
        ]);
    }

    // READ - Récupère les candidatures d'un étudiant
    public function getByEtudiant(int $etudiant_id): array {
        $stmt = $this->db->prepare(
            "SELECT candidatures.*, 
                offres.titre AS offre_titre,
                entreprises.nom AS entreprise_nom
             FROM candidatures
             JOIN offres ON candidatures.offre_id = offres.id
             JOIN entreprises ON offres.entreprise_id = entreprises.id
             WHERE candidatures.etudiant_id = :etudiant_id
             ORDER BY candidatures.created_at DESC"
        );
        $stmt->execute([':etudiant_id' => $etudiant_id]);
        return $stmt->fetchAll();
    }

    // READ - Récupère les candidatures des élèves d'un pilote
    public function getByPilote(int $pilote_id): array {
        $stmt = $this->db->prepare(
            "SELECT candidatures.*,
                offres.titre AS offre_titre,
                entreprises.nom AS entreprise_nom,
                utilisateurs.nom AS etudiant_nom,
                utilisateurs.prenom AS etudiant_prenom
             FROM candidatures
             JOIN offres ON candidatures.offre_id = offres.id
             JOIN entreprises ON offres.entreprise_id = entreprises.id
             JOIN utilisateurs ON candidatures.etudiant_id = utilisateurs.id
             WHERE utilisateurs.role = 'etudiant'
             ORDER BY candidatures.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
