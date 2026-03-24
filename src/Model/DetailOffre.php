<?php
namespace App\Model;
require_once 'ConnexionDB.php';
use App\Model\ConnexionDB;


class DetailOffre {
    private $db;

    public function __construct() {
          $this->db = (new ConnexionDB())->getConnection();
    }

    public function getOffreDetails($offreId) {
        $stmt = $this->db->prepare("SELECT o.*, e.nom_societe AS entreprises, e.description AS description_entreprise FROM offres o JOIN entreprises e ON o.id_entreprise = e.id_entreprise WHERE o.id_offre = :offreId");
        $stmt->bindParam(':offreId', $offreId);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}