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

    public function createEntreprise($nomSociete, $secteur, $description, $contact)
    {
        $sql = 'INSERT INTO entreprises (nom_societe, secteur, description, contact)
                VALUES (:nom_societe, :secteur, :description, :contact)';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nom_societe', trim((string) $nomSociete));
        $stmt->bindValue(':secteur', trim((string) $secteur));
        $stmt->bindValue(':description', trim((string) $description));
        $stmt->bindValue(':contact', trim((string) $contact));

        return $stmt->execute();
    }

    public function updateEntreprise($idEntreprise, $nomSociete, $secteur, $description, $contact)
    {
        $sql = 'UPDATE entreprises
                SET nom_societe = :nom_societe,
                    secteur = :secteur,
                    description = :description,
                    contact = :contact
                WHERE id_entreprise = :id_entreprise';

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_entreprise', (int) $idEntreprise, \PDO::PARAM_INT);
        $stmt->bindValue(':nom_societe', trim((string) $nomSociete));
        $stmt->bindValue(':secteur', trim((string) $secteur));
        $stmt->bindValue(':description', trim((string) $description));
        $stmt->bindValue(':contact', trim((string) $contact));

        return $stmt->execute();
    }

    public function deleteEntreprise($idEntreprise)
    {
        $sql = 'DELETE FROM entreprises WHERE id_entreprise = :id_entreprise';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_entreprise', (int) $idEntreprise, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getRecentEvaluationsByEntrepriseIds(array $entrepriseIds, $limitPerEntreprise = 3)
    {
        $entrepriseIds = array_values(array_unique(array_map('intval', $entrepriseIds)));
        if (empty($entrepriseIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($entrepriseIds), '?'));
        $sql = "SELECT ev.id_evaluation, ev.id_entreprise, ev.note, ev.commentaire, ev.date_evaluation,
                       u.nom, u.prenom, u.role
                FROM evaluation ev
                INNER JOIN Utilisateurs u ON u.id_utilisateur = ev.id_utilisateur
                WHERE ev.id_entreprise IN ($placeholders)
                ORDER BY ev.id_entreprise ASC, ev.date_evaluation DESC, ev.id_evaluation DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($entrepriseIds as $index => $idEntreprise) {
            $stmt->bindValue($index + 1, $idEntreprise, \PDO::PARAM_INT);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $grouped = [];

        foreach ($rows as $row) {
            $idEntreprise = (int) ($row['id_entreprise'] ?? 0);
            if ($idEntreprise <= 0) {
                continue;
            }

            if (!isset($grouped[$idEntreprise])) {
                $grouped[$idEntreprise] = [];
            }

            if (count($grouped[$idEntreprise]) >= (int) $limitPerEntreprise) {
                continue;
            }

            $grouped[$idEntreprise][] = $row;
        }

        return $grouped;
    }
}
