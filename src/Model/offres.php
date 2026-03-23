<?php 

namespace App\Model;

include_once 'connexionDB.php';

use App\Model\ConnexionDB;

class offres {
    private $db;

    public function __construct() {
        $this->db = (new connexionDB())->getConnection();
    }

    public function getAllOffres() {
        $srql = 'SELECT * FROM offres';
        $stmt = $this->db->prepare($srql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}