<?php

namespace App\Model;

require_once 'ConnexionDB.php';

class Evaluation
{
    private $db;

    public function __construct()
    {
        $this->db = (new ConnexionDB())->getConnection();
    }

    public function addEvaluation($idUtilisateur, $idEntreprise, $note, $commentaire)
    {
        $sql = 'INSERT INTO evaluation (note, commentaire, date_evaluation, id_utilisateur, id_entreprise)
                VALUES (:note, :commentaire, CURDATE(), :id_utilisateur, :id_entreprise)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':note', (int) $note);
        $stmt->bindValue(':commentaire', $commentaire ?? '');
        $stmt->bindValue(':id_utilisateur', (int) $idUtilisateur);
        $stmt->bindValue(':id_entreprise', (int) $idEntreprise);

        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


  
    public function updateEvaluation($idEvaluation, $note, $commentaire)
    {
        $sql = 'UPDATE evaluation SET note = :note, commentaire = :commentaire, date_evaluation = CURDATE()
                WHERE id_evaluation = :id_evaluation';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_evaluation', (int)$idEvaluation);
        $stmt->bindValue(':note', (int)$note);
        $stmt->bindValue(':commentaire', $commentaire ?? '');
        
        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

 
    public function getEvaluation($idUtilisateur, $idEntreprise)
    {
        $sql = 'SELECT * FROM evaluation
                WHERE id_utilisateur = :id_utilisateur AND id_entreprise = :id_entreprise
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_utilisateur', (int)$idUtilisateur);
        $stmt->bindValue(':id_entreprise', (int)$idEntreprise);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

 
    public function deleteEvaluation($idEvaluation)
    {
        $sql = 'DELETE FROM evaluation WHERE id_evaluation = :id_evaluation';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_evaluation', (int)$idEvaluation);
        
        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

  
    public function getEvaluationsByPilote($idPilote)
    {
        $sql = 'SELECT e.*, en.nom_societe FROM evaluation e
                JOIN entreprises en ON e.id_entreprise = en.id_entreprise
                WHERE e.id_utilisateur = :id_utilisateur
                ORDER BY e.date_evaluation DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_utilisateur', (int)$idPilote);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

   
    public function getAverageRatingByEntreprise($idEntreprise)
    {
        $sql = 'SELECT AVG(note) as avg_note, COUNT(note) as total_evals FROM evaluation
                WHERE id_entreprise = :id_entreprise';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_entreprise', (int)$idEntreprise);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: ['avg_note' => 0, 'total_evals' => 0];
    }
}
