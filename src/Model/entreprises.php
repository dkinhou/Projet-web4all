<?php

namespace App\Model;

require_once 'ConnexionDB.php';

class entreprises
{
    private $db;

    public function __construct()
    {
        $this->db = (new ConnexionDB())->getConnection();
    }

    private function buildFiltersSql(array $filters, array &$bindings)
    {
        $conditions = [];

        $keyword = trim((string) ($filters['keyword'] ?? ''));
        if ($keyword !== '') {
            $conditions[] = '(e.nom_societe LIKE :keyword OR e.secteur LIKE :keyword OR e.description LIKE :keyword OR e.contact LIKE :keyword)';
            $bindings[':keyword'] = '%' . $keyword . '%';
        }

        $secteur = trim((string) ($filters['secteur'] ?? ''));
        if ($secteur !== '') {
            $conditions[] = 'e.secteur LIKE :secteur';
            $bindings[':secteur'] = '%' . $secteur . '%';
        }

        if (empty($conditions)) {
            return '';
        }

        return ' WHERE ' . implode(' AND ', $conditions);
    }

    public function countAllEntreprises()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM entreprises');
        return (int) $stmt->fetchColumn();
    }

    public function countFilteredEntreprises(array $filters)
    {
        $bindings = [];
        $whereSql = $this->buildFiltersSql($filters, $bindings);
        $sql = 'SELECT COUNT(DISTINCT e.id_entreprise)
            FROM entreprises e' . $whereSql;

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getEntreprisesPaginated($limit, $offset)
    {
        $sql = 'SELECT e.id_entreprise, e.nom_societe, e.secteur, e.description, e.contact,
                       COALESCE(ev.moyenne_note, 0) AS moyenne_note,
                       COALESCE(ev.total_evaluations, 0) AS total_evaluations
            FROM entreprises e
            LEFT JOIN (
                SELECT id_entreprise, AVG(note) AS moyenne_note, COUNT(*) AS total_evaluations
                FROM evaluation
                GROUP BY id_entreprise
            ) ev ON ev.id_entreprise = e.id_entreprise
                ORDER BY e.nom_societe ASC
                LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getFilteredEntreprisesPaginated(array $filters, $limit, $offset)
    {
        $bindings = [];
        $whereSql = $this->buildFiltersSql($filters, $bindings);

        $sql = 'SELECT e.id_entreprise, e.nom_societe, e.secteur, e.description, e.contact,
                       COALESCE(ev.moyenne_note, 0) AS moyenne_note,
                       COALESCE(ev.total_evaluations, 0) AS total_evaluations
            FROM entreprises e
            LEFT JOIN (
                SELECT id_entreprise, AVG(note) AS moyenne_note, COUNT(*) AS total_evaluations
                FROM evaluation
                GROUP BY id_entreprise
            ) ev ON ev.id_entreprise = e.id_entreprise' .
                $whereSql .
               ' ORDER BY e.nom_societe ASC
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

    public function getEntrepriseById($idEntreprise)
    {
        $sql = 'SELECT * FROM entreprises WHERE id_entreprise = :id_entreprise LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_entreprise', (int) $idEntreprise);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
