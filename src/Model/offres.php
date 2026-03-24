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
        $srql = 'SELECT * FROM offres LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($srql);
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