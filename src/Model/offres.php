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

    public function getOffresByIds(array $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $ids = array_values(array_unique(array_map('intval', $ids)));
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT offres.*, entreprises.nom_societe AS entreprises
                FROM offres
                JOIN entreprises ON offres.id_entreprise = entreprises.id_entreprise
                WHERE offres.id_offre IN ($placeholders)
                ORDER BY offres.date_publication DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, $id, \PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOffreById($offreId)
    {
        $stmt = $this->db->prepare(
            'SELECT offres.*, entreprises.nom_societe AS entreprises
             FROM offres
             JOIN entreprises ON offres.id_entreprise = entreprises.id_entreprise
             WHERE offres.id_offre = :offreId
             LIMIT 1'
        );
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);
        $stmt->execute();

        $offre = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $offre ?: null;
    }

    public function updateOffre($offreId, array $data)
    {
        $stmt = $this->db->prepare(
            'UPDATE offres
             SET titre = :titre,
                 description = :description,
                 ville = :ville,
                 type_contrat = :typeContrat,
                 duree = :duree,
                 remuneration = :remuneration
             WHERE id_offre = :offreId'
        );

        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);
        $stmt->bindValue(':titre', (string) ($data['titre'] ?? ''));
        $stmt->bindValue(':description', (string) ($data['description'] ?? ''));
        $stmt->bindValue(':ville', (string) ($data['ville'] ?? ''));
        $stmt->bindValue(':typeContrat', (string) ($data['type_contrat'] ?? 'Stage'));
        $stmt->bindValue(':duree', (string) ($data['duree'] ?? ''));
        $stmt->bindValue(':remuneration', (string) ($data['remuneration'] ?? ''));

        return $stmt->execute();
    }

    public function deleteOffre($offreId)
    {
        $stmt = $this->db->prepare('DELETE FROM offres WHERE id_offre = :offreId');
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);

        return $stmt->execute();
    }
}