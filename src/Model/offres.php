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
        $sql = 'SELECT * FROM offres ORDER BY date_publication DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEntreprisesOptions()
    {
        $sql = 'SELECT id_entreprise, nom_societe FROM entreprises ORDER BY nom_societe ASC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function buildFiltersSql(array $filters, array &$bindings)
    {
        $conditions = [];

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $conditions[] = '(offres.titre LIKE :keyword OR offres.description LIKE :keyword OR offres.missions LIKE :keyword OR offres.profil_recherche LIKE :keyword)';
            $bindings[':keyword'] = '%' . $keyword . '%';
        }

        $location = trim((string) ($filters['location'] ?? $filters['ville'] ?? ''));
        if ($location !== '') {
            $conditions[] = 'offres.ville LIKE :location';
            $bindings[':location'] = '%' . $location . '%';
        }

        $entreprise = trim((string) ($filters['entreprise'] ?? ''));
        if ($entreprise !== '') {
            $conditions[] = 'entreprises.nom_societe LIKE :entreprise';
            $bindings[':entreprise'] = '%' . $entreprise . '%';
        }

        $specialite = trim((string) ($filters['specialite'] ?? ''));
        if ($specialite !== '') {
            $conditions[] = '(offres.description LIKE :specialite OR offres.missions LIKE :specialite OR offres.profil_recherche LIKE :specialite)';
            $bindings[':specialite'] = '%' . $specialite . '%';
        }

        $niveau = trim((string) ($filters['niveau'] ?? ''));
        if ($niveau !== '') {
            $conditions[] = 'offres.profil_recherche LIKE :niveau';
            $bindings[':niveau'] = '%' . $niveau . '%';
        }

        $typeContrat = trim((string) ($filters['type_contrat'] ?? $filters['type'] ?? ''));
        if ($typeContrat !== '' && in_array($typeContrat, ['Stage', 'Alternance'], true)) {
            $conditions[] = 'offres.type_contrat = :typeContrat';
            $bindings[':typeContrat'] = $typeContrat;
        }

        if (empty($conditions)) {
            return '';
        }

        return ' WHERE ' . implode(' AND ', $conditions);
    }

    public function getOffresPaginated($limit, $offset) {
        $sql = 'SELECT offres.*, entreprises.nom_societe AS entreprises FROM offres JOIN entreprises ON offres.id_entreprise = entreprises.id_entreprise ORDER BY offres.date_publication DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOffresFilteredPaginated(array $filters, $limit, $offset)
    {
        $bindings = [];
        $whereSql = $this->buildFiltersSql($filters, $bindings);
        $sql = 'SELECT offres.*, entreprises.nom_societe AS entreprises
                FROM offres
                JOIN entreprises ON offres.id_entreprise = entreprises.id_entreprise' .
                $whereSql .
               ' ORDER BY offres.date_publication DESC
                LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countOffresFiltered(array $filters)
    {
        $bindings = [];
        $whereSql = $this->buildFiltersSql($filters, $bindings);
        $sql = 'SELECT COUNT(*)
                FROM offres
                JOIN entreprises ON offres.id_entreprise = entreprises.id_entreprise' . $whereSql;

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getOffresId()
    {
        $sql = 'SELECT id_offre FROM offres ORDER BY date_publication DESC';
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
                 id_entreprise = :id_entreprise,
                 ville = :ville,
                 type_contrat = :typeContrat,
                 duree = :duree,
                 missions = :missions,
                 profil_recherche = :profil_recherche,
                 remuneration = :remuneration
             WHERE id_offre = :offreId'
        );

        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);
        $stmt->bindValue(':titre', (string) ($data['titre'] ?? ''));
        $stmt->bindValue(':description', (string) ($data['description'] ?? ''));
        $stmt->bindValue(':id_entreprise', (int) ($data['id_entreprise'] ?? 0), \PDO::PARAM_INT);
        $stmt->bindValue(':ville', (string) ($data['ville'] ?? ''));
        $stmt->bindValue(':typeContrat', (string) ($data['type_contrat'] ?? 'Stage'));
        $stmt->bindValue(':duree', (string) ($data['duree'] ?? ''));
        $stmt->bindValue(':missions', (string) ($data['missions'] ?? ''));
        $stmt->bindValue(':profil_recherche', (string) ($data['profil_recherche'] ?? ''));
        $stmt->bindValue(':remuneration', (string) ($data['remuneration'] ?? ''));

        return $stmt->execute();
    }

    public function createOffre(array $data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO offres (
                titre,
                description,
                ville,
                date_publication,
                id_entreprise,
                type_contrat,
                duree,
                missions,
                profil_recherche,
                remuneration,
                avantages
            ) VALUES (
                :titre,
                :description,
                :ville,
                CURDATE(),
                :id_entreprise,
                :type_contrat,
                :duree,
                :missions,
                :profil_recherche,
                :remuneration,
                :avantages
            )'
        );

        $stmt->bindValue(':titre', (string) ($data['titre'] ?? ''));
        $stmt->bindValue(':description', (string) ($data['description'] ?? ''));
        $stmt->bindValue(':ville', (string) ($data['ville'] ?? ''));
        $stmt->bindValue(':id_entreprise', (int) ($data['id_entreprise'] ?? 0), \PDO::PARAM_INT);
        $stmt->bindValue(':type_contrat', (string) ($data['type_contrat'] ?? 'Stage'));
        $stmt->bindValue(':duree', (string) ($data['duree'] ?? ''));
        $stmt->bindValue(':missions', (string) ($data['missions'] ?? ''));
        $stmt->bindValue(':profil_recherche', (string) ($data['profil_recherche'] ?? ''));
        $stmt->bindValue(':remuneration', (string) ($data['remuneration'] ?? ''));
        $stmt->bindValue(':avantages', (string) ($data['avantages'] ?? ''));

        return $stmt->execute();
    }

    public function deleteOffre($offreId)
    {
        $stmt = $this->db->prepare('DELETE FROM offres WHERE id_offre = :offreId');
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);

        return $stmt->execute();
    }
}