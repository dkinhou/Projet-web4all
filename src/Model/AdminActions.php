<?php
namespace App\Model;

require_once 'ConnexionDB.php';

class AdminActions {
    private $db;

    public function __construct() {
        $dbConnection = new ConnexionDB();
        $this->db = $dbConnection->getConnection();
    }

    private function resolvePiloteId($inputId)
    {
        if ($inputId === null || (int) $inputId <= 0) {
            return null;
        }

        $inputId = (int) $inputId;

        $stmt = $this->db->prepare('SELECT id_pilote FROM pilotes WHERE id_pilote = :id LIMIT 1');
        $stmt->bindValue(':id', $inputId, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && isset($row['id_pilote'])) {
            return (int) $row['id_pilote'];
        }

        $stmt = $this->db->prepare('SELECT id_pilote FROM pilotes WHERE id_utilisateur = :id LIMIT 1');
        $stmt->bindValue(':id', $inputId, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return ($row && isset($row['id_pilote'])) ? (int) $row['id_pilote'] : null;
    }

    // ==================== GESTION DES PILOTES ====================
    
    public function getAllPilotes($limit = 15, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT u.*, p.id_pilote, COUNT(e.id_etudiant) as total_etudiants
            FROM Utilisateurs u
            INNER JOIN pilotes p ON p.id_utilisateur = u.id_utilisateur
            LEFT JOIN etudiants e ON p.id_pilote = e.id_pilote
            WHERE u.role = 'Pilote'
            GROUP BY u.id_utilisateur, p.id_pilote
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countPilotes() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM Utilisateurs WHERE role = 'Pilote'");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getPiloteById($idUtilisateur) {
        $stmt = $this->db->prepare("
            SELECT u.*, p.id_pilote
            FROM Utilisateurs u
            INNER JOIN pilotes p ON p.id_utilisateur = u.id_utilisateur
            WHERE u.id_utilisateur = :id AND u.role = 'Pilote'
            LIMIT 1
        ");
        $stmt->bindParam(':id', $idUtilisateur, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function createPilote($email, $password, $nom, $prenom) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO Utilisateurs (email, mdp, role, nom, prenom)
                VALUES (:email, :mdp, 'Pilote', :nom, :prenom)
            ");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mdp', $hashedPassword);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);

            if (!$stmt->execute()) {
                $this->db->rollBack();
                return false;
            }

            $idUtilisateur = (int) $this->db->lastInsertId();

            $stmtPilote = $this->db->prepare("
                INSERT INTO pilotes (id_pilote, id_utilisateur)
                VALUES (:id_pilote, :id_utilisateur)
            ");
            $stmtPilote->bindValue(':id_pilote', $idUtilisateur, \PDO::PARAM_INT);
            $stmtPilote->bindValue(':id_utilisateur', $idUtilisateur, \PDO::PARAM_INT);

            if (!$stmtPilote->execute()) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function updatePilote($idUtilisateur, $email, $nom, $prenom) {
        $stmt = $this->db->prepare("
            UPDATE Utilisateurs 
            SET email = :email, nom = :nom, prenom = :prenom
            WHERE id_utilisateur = :id AND role = 'Pilote'
        ");
        $stmt->bindParam(':id', $idUtilisateur, \PDO::PARAM_INT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        return $stmt->execute();
    }

    public function deletePilote($idUtilisateur) {
        $stmt = $this->db->prepare("DELETE FROM Utilisateurs WHERE id_utilisateur = :id AND role = 'Pilote'");
        $stmt->bindParam(':id', $idUtilisateur, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ==================== GESTION DES ETUDIANTS ====================
    
    public function getAllEtudiants($limit = 15, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT u.*, e.id_pilote, p.prenom as pilote_prenom, p.nom as pilote_nom
            FROM Utilisateurs u
            LEFT JOIN etudiants e ON u.id_utilisateur = e.id_etudiant
            LEFT JOIN pilotes pp ON e.id_pilote = pp.id_pilote
            LEFT JOIN Utilisateurs p ON pp.id_utilisateur = p.id_utilisateur
            WHERE u.role = 'Etudiant'
            ORDER BY u.nom, u.prenom
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countEtudiants() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM Utilisateurs WHERE role = 'Etudiant'");
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getEtudiantById($idUtilisateur) {
        $stmt = $this->db->prepare("
            SELECT u.*, e.id_pilote, p.prenom as pilote_prenom, p.nom as pilote_nom
            FROM Utilisateurs u
            LEFT JOIN etudiants e ON u.id_utilisateur = e.id_etudiant
            LEFT JOIN pilotes pp ON e.id_pilote = pp.id_pilote
            LEFT JOIN Utilisateurs p ON pp.id_utilisateur = p.id_utilisateur
            WHERE u.id_utilisateur = :id AND u.role = 'Etudiant'
        ");
        $stmt->bindParam(':id', $idUtilisateur, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function createEtudiant($email, $password, $nom, $prenom, $idPilote = null) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $resolvedPiloteId = $this->resolvePiloteId($idPilote);
        if ($resolvedPiloteId === null) {
            return false;
        }
        
        // Créer l'utilisateur
        $stmt = $this->db->prepare("
            INSERT INTO Utilisateurs (email, mdp, role, nom, prenom) 
            VALUES (:email, :mdp, 'Etudiant', :nom, :prenom)
        ");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdp', $hashedPassword);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        
        if (!$stmt->execute()) {
            return false;
        }
        
        $idUtilisateur = $this->db->lastInsertId();
        
        // Créer l'enregistrement étudiant
        $stmt2 = $this->db->prepare("
            INSERT INTO etudiants (id_etudiant, id_utilisateur, id_pilote, id_whishlist) 
            VALUES (:id_etudiant, :id_utilisateur, :id_pilote, 0)
        ");
        $stmt2->bindParam(':id_etudiant', $idUtilisateur, \PDO::PARAM_INT);
        $stmt2->bindParam(':id_utilisateur', $idUtilisateur, \PDO::PARAM_INT);
        $stmt2->bindParam(':id_pilote', $resolvedPiloteId, \PDO::PARAM_INT);
        
        return $stmt2->execute();
    }

    public function updateEtudiant($idUtilisateur, $email, $nom, $prenom, $idPilote = null) {
        $resolvedPiloteId = $this->resolvePiloteId($idPilote);
        if ($resolvedPiloteId === null) {
            return false;
        }

        // Mettre à jour l'utilisateur
        $stmt = $this->db->prepare("
            UPDATE Utilisateurs 
            SET email = :email, nom = :nom, prenom = :prenom
            WHERE id_utilisateur = :id AND role = 'Etudiant'
        ");
        $stmt->bindParam(':id', $idUtilisateur, \PDO::PARAM_INT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        
        if (!$stmt->execute()) {
            return false;
        }
        
        // Mettre à jour l'étudiant (pilote)
        $stmt2 = $this->db->prepare("
            UPDATE etudiants 
            SET id_pilote = :id_pilote
            WHERE id_etudiant = :id_utilisateur
        ");
        $stmt2->bindParam(':id_utilisateur', $idUtilisateur, \PDO::PARAM_INT);
        $stmt2->bindParam(':id_pilote', $resolvedPiloteId, \PDO::PARAM_INT);
        
        return $stmt2->execute();
    }

    public function deleteEtudiant($idUtilisateur) {
        $stmt = $this->db->prepare("DELETE FROM Utilisateurs WHERE id_utilisateur = :id AND role = 'Etudiant'");
        $stmt->bindParam(':id', $idUtilisateur, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    // ==================== GESTION DES ETUDIANTS PAR PILOTE ====================
    
    public function getEtudiantsByPilote($idPilote, $limit = 15, $offset = 0) {
        $resolvedPiloteId = $this->resolvePiloteId($idPilote);
        if ($resolvedPiloteId === null) {
            return [];
        }
        
        $stmt = $this->db->prepare("
            SELECT u.*, e.id_pilote
            FROM Utilisateurs u
            INNER JOIN etudiants e ON u.id_utilisateur = e.id_etudiant
            WHERE e.id_pilote = :id_pilote AND u.role = 'Etudiant'
            ORDER BY u.nom, u.prenom
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindParam(':id_pilote', $resolvedPiloteId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countEtudiantsByPilote($idPilote) {
        $resolvedPiloteId = $this->resolvePiloteId($idPilote);
        if ($resolvedPiloteId === null) {
            return 0;
        }
        
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count FROM etudiants 
            WHERE id_pilote = :id_pilote
        ");
        $stmt->bindParam(':id_pilote', $resolvedPiloteId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    public function getEtudiantByIdAndPilote($idUtilisateur, $idPilote) {
        $resolvedPiloteId = $this->resolvePiloteId($idPilote);
        if ($resolvedPiloteId === null) {
            return null;
        }
        
        $stmt = $this->db->prepare("
            SELECT u.*, e.id_pilote
            FROM Utilisateurs u
            INNER JOIN etudiants e ON u.id_utilisateur = e.id_etudiant
            WHERE u.id_utilisateur = :id_utilisateur 
            AND e.id_pilote = :id_pilote 
            AND u.role = 'Etudiant'
            LIMIT 1
        ");
        $stmt->bindParam(':id_utilisateur', $idUtilisateur, \PDO::PARAM_INT);
        $stmt->bindParam(':id_pilote', $resolvedPiloteId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // ==================== STATISTIQUES ====================
    
    public function getStatistiques() {
        $stats = [];
        
        // Total des entreprises
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM entreprises");
        $stats['total_entreprises'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['count'];
        
        // Total des offres
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM offres");
        $stats['total_offres'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['count'];
        
        // Total des candidatures
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM candidatures");
        $stats['total_candidatures'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['count'];
        
        // Total des pilotes
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM Utilisateurs WHERE role = 'Pilote'");
        $stats['total_pilotes'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['count'];
        
        // Total des étudiants
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM Utilisateurs WHERE role = 'Etudiant'");
        $stats['total_etudiants'] = (int)$stmt->fetch(\PDO::FETCH_ASSOC)['count'];
        
        return $stats;
    }
}
