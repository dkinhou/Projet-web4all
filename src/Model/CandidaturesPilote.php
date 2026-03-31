<?php

namespace App\Model;

require_once 'ConnexionDB.php';

class CandidaturesPilote
{
    private $db;

    public function __construct()
    {
        $this->db = (new ConnexionDB())->getConnection();
    }

    private function resolvePiloteId($inputId)
    {
        $inputId = (int) $inputId;
        if ($inputId <= 0) {
            return 0;
        }

        $stmt = $this->db->prepare('SELECT id_pilote FROM pilotes WHERE id_pilote = :id LIMIT 1');
        $stmt->bindValue(':id', $inputId, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && isset($row['id_pilote'])) {
            return (int) $row['id_pilote'];
        }

        $stmt = $this->db->prepare('SELECT id_pilote FROM pilotes WHERE id_utilisateur = :id LIMIT 1');
        $stmt->bindValue(':id', $inputId, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return ($row && isset($row['id_pilote'])) ? (int) $row['id_pilote'] : 0;
    }

    public function getCandidaturesByPilote($idPilote, $limit = 20, $offset = 0)
    {
    $idPilote = $this->resolvePiloteId($idPilote);
    $sql = 'SELECT c.*, offres.titre, offres.id_entreprise, entreprises.nom_societe,
               u.nom AS nom_etudiant, u.prenom AS prenom_etudiant, u.email
                FROM candidatures c
                JOIN offres ON c.id_offre = offres.id_offre
                JOIN entreprises ON offres.id_entreprise = entreprises.id_entreprise
        JOIN Utilisateurs u ON c.id_utilisateur = u.id_utilisateur
        JOIN etudiants ON etudiants.id_etudiant = c.id_utilisateur
        WHERE etudiants.id_pilote = :id_pilote
                ORDER BY c.date_candidature DESC
                LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_pilote', (int)$idPilote);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countCandidaturesByPilote($idPilote)
    {
        $idPilote = $this->resolvePiloteId($idPilote);
        $sql = 'SELECT COUNT(*) FROM candidatures c
            JOIN etudiants ON etudiants.id_etudiant = c.id_utilisateur
                WHERE etudiants.id_pilote = :id_pilote';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_pilote', (int)$idPilote);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function updateStatutCandidature($idCandidature, $statut)
    {
        $validsStatuts = ['En attente', 'Acceptee', 'Rejetee'];
        if (!in_array($statut, $validsStatuts)) {
            return false;
        }

        $sql = 'UPDATE candidatures SET statut = :statut WHERE id_candidature = :id_candidature';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_candidature', (int)$idCandidature);
        $stmt->bindValue(':statut', $statut);

        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function getStatsPilote($idPilote)
    {
        $idPilote = $this->resolvePiloteId($idPilote);
        $sql = 'SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN c.statut = "En attente" THEN 1 ELSE 0 END) as en_attente,
                    SUM(CASE WHEN c.statut = "Acceptee" THEN 1 ELSE 0 END) as acceptees,
                    SUM(CASE WHEN c.statut = "Rejetee" THEN 1 ELSE 0 END) as rejetees
                FROM candidatures c
                JOIN etudiants ON etudiants.id_etudiant = c.id_utilisateur
                WHERE etudiants.id_pilote = :id_pilote';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_pilote', (int)$idPilote);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
