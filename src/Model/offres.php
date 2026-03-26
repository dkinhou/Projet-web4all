<?php 

namespace App\Model;

require_once 'ConnexionDB.php';

use App\Model\ConnexionDB;

class offres {
    private $db;

    public function __construct() {
        $this->db = (new ConnexionDB())->getConnection();
    }



    public function getAllOffres() {
        $sql = 'SELECT * FROM offres';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOffresPaginated($limit, $offset) {
        $sql = 'SELECT offres.*, entreprises.nom_societe AS entreprises FROM offres JOIN entreprises ON offres.id_entreprise = entreprises.id_entreprise LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOffresId()
    {
        $sql = 'SELECT id_offre FROM offres';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}