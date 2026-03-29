<?php
namespace App\Model;

require_once 'ConnexionDB.php';

class EtudiantActions
{
    private $db;

    public function __construct()
    {
        $this->db = (new ConnexionDB())->getConnection();
    }

    private function resolveEtudiantId($userId)
    {
        $stmt = $this->db->prepare("SELECT id_etudiant FROM etudiants WHERE id_utilisateur = :userId OR id_etudiant = :userId LIMIT 1");
        $stmt->bindValue(':userId', (int) $userId, \PDO::PARAM_INT);
        $stmt->execute();

        $etudiantId = $stmt->fetchColumn();
        if ($etudiantId === false) {
            return null;
        }

        return (int) $etudiantId;
    }

    public function hasEtudiantProfile($userId)
    {
        return $this->resolveEtudiantId($userId) !== null;
    }

    public function getEtudiantProfile($userId)
    {
        $etudiantId = $this->resolveEtudiantId($userId);
        if ($etudiantId === null) {
            return ['photo' => null, 'cv' => null];
        }

        $stmt = $this->db->prepare("SELECT photo, cv FROM etudiants WHERE id_etudiant = :etudiantId LIMIT 1");
        $stmt->bindValue(':etudiantId', $etudiantId, \PDO::PARAM_INT);
        $stmt->execute();

        $profile = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$profile) {
            return ['photo' => null, 'cv' => null];
        }

        return [
            'photo' => $profile['photo'] ?? null,
            'cv' => $profile['cv'] ?? null,
        ];
    }

    public function updateEtudiantFiles($userId, $photoPath = null, $cvPath = null)
    {
        $etudiantId = $this->resolveEtudiantId($userId);
        if ($etudiantId === null) {
            return false;
        }

        $fields = [];
        if ($photoPath !== null) {
            $fields[] = 'photo = :photo';
        }
        if ($cvPath !== null) {
            $fields[] = 'cv = :cv';
        }

        if (empty($fields)) {
            return true;
        }

        $sql = 'UPDATE etudiants SET ' . implode(', ', $fields) . ' WHERE id_etudiant = :etudiantId';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':etudiantId', $etudiantId, \PDO::PARAM_INT);

        if ($photoPath !== null) {
            $stmt->bindValue(':photo', $photoPath);
        }
        if ($cvPath !== null) {
            $stmt->bindValue(':cv', $cvPath);
        }

        return $stmt->execute();
    }

    public function clearEtudiantFile($userId, $field)
    {
        if (!in_array($field, ['photo', 'cv'], true)) {
            return false;
        }

        $etudiantId = $this->resolveEtudiantId($userId);
        if ($etudiantId === null) {
            return false;
        }

        $sql = 'UPDATE etudiants SET ' . $field . ' = NULL WHERE id_etudiant = :etudiantId';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':etudiantId', $etudiantId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getAppliedOfferIds($userId)
    {
        $stmt = $this->db->prepare("SELECT id_offre FROM candidatures WHERE id_utilisateur = :userId ORDER BY date_candidature DESC");
        $stmt->bindValue(':userId', (int) $userId, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map('intval', array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'id_offre'));
    }

    public function getApplicationsWithStatus($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT c.id_offre, c.statut, c.date_candidature, o.titre, e.nom_societe AS entreprises
             FROM candidatures c
             JOIN offres o ON o.id_offre = c.id_offre
             JOIN entreprises e ON e.id_entreprise = o.id_entreprise
             WHERE c.id_utilisateur = :userId
             ORDER BY c.date_candidature DESC"
        );
        $stmt->bindValue(':userId', (int) $userId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function hasApplied($userId, $offreId)
    {
        $stmt = $this->db->prepare("SELECT 1 FROM candidatures WHERE id_utilisateur = :userId AND id_offre = :offreId LIMIT 1");
        $stmt->bindValue(':userId', (int) $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function applyToOffer($userId, $offreId)
    {
        if ($this->hasApplied($userId, $offreId)) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO candidatures (date_candidature, id_utilisateur, id_offre, statut) VALUES (CURDATE(), :userId, :offreId, 'En attente')");
        $stmt->bindValue(':userId', (int) $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getWishlistOfferIds($userId)
    {
        $etudiantId = $this->resolveEtudiantId($userId);
        if ($etudiantId === null) {
            return [];
        }

        $stmt = $this->db->prepare("SELECT id_offre FROM wishlist WHERE id_utilisateur = :userId ORDER BY date_ajout DESC");
        $stmt->bindValue(':userId', $etudiantId, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map('intval', array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'id_offre'));
    }

    public function isInWishlist($userId, $offreId)
    {
        $etudiantId = $this->resolveEtudiantId($userId);
        if ($etudiantId === null) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT 1 FROM wishlist WHERE id_utilisateur = :userId AND id_offre = :offreId LIMIT 1");
        $stmt->bindValue(':userId', $etudiantId, \PDO::PARAM_INT);
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function addToWishlist($userId, $offreId)
    {
        $etudiantId = $this->resolveEtudiantId($userId);
        if ($etudiantId === null) {
            return false;
        }

        if ($this->isInWishlist($userId, $offreId)) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO wishlist (date_ajout, id_utilisateur, id_offre) VALUES (CURDATE(), :userId, :offreId)");
        $stmt->bindValue(':userId', $etudiantId, \PDO::PARAM_INT);
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function removeFromWishlist($userId, $offreId)
    {
        $etudiantId = $this->resolveEtudiantId($userId);
        if ($etudiantId === null) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM wishlist WHERE id_utilisateur = :userId AND id_offre = :offreId");
        $stmt->bindValue(':userId', $etudiantId, \PDO::PARAM_INT);
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function countOfferApplicants($offreId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM candidatures WHERE id_offre = :offreId");
        $stmt->bindValue(':offreId', (int) $offreId, \PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
