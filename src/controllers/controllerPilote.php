<?php
use App\Model\AdminActions;

require_once 'Controller.php'; 
require_once __DIR__ . '/../Model/AdminActions.php';

class controllerPilote extends Controller {
    private $adminActions;

    public function __construct() {
        parent::__construct();
        $this->adminActions = new AdminActions();
    }

    // ==================== GESTION DES ETUDIANTS ====================
    
    public function listeEtudiants($idPilote) {
        $itemsPerPage = 15;
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;

        $offset = ($currentPage - 1) * $itemsPerPage;
        $totalCount = $this->adminActions->countEtudiantsByPilote($idPilote);
        $etudiants = $this->adminActions->getEtudiantsByPilote($idPilote, $itemsPerPage, $offset);

        $manage = $_GET['manage'] ?? '';
        $manageMessage = '';
        if ($manage === 'created') {
            $manageMessage = 'Etudiant cree avec succes.';
        } elseif ($manage === 'updated') {
            $manageMessage = 'Etudiant modifie avec succes.';
        } elseif ($manage === 'deleted') {
            $manageMessage = 'Etudiant supprime avec succes.';
        }

        $this->render('pilote_etudiants.twig.html', [
            'etudiants' => $etudiants,
            'compteur' => $totalCount,
            'currentPage' => $currentPage,
            'totalPages' => max(1, (int) ceil($totalCount / $itemsPerPage)),
            'manageMessage' => $manageMessage,
        ]);
    }

    public function creerEtudiant($idPilote) {
        $message = '';
        $formData = ['email' => '', 'nom' => '', 'prenom' => '', 'password' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $prenom = trim((string) ($_POST['prenom'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if (!$email || !$nom || !$prenom || !$password) {
                $message = 'Tous les champs sont obligatoires.';
            } elseif (strlen($password) < 6) {
                $message = 'Le mot de passe doit contenir au moins 6 caracteres.';
            } else {
                if ($this->adminActions->createEtudiant($email, $password, $nom, $prenom, $idPilote)) {
                    header('Location: /etudiants?manage=created');
                    exit();
                } else {
                    $message = 'Erreur lors de la creation de l etudiant. Verifiez que l email n existe pas deja.';
                    $formData = ['email' => $email, 'nom' => $nom, 'prenom' => $prenom, 'password' => ''];
                }
            }
        }

        $this->render('pilote_creer_etudiant.twig.html', [
            'message' => $message,
            'formData' => $formData,
        ]);
    }

    public function modifierEtudiant($idPilote) {
        $idEtudiant = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($idEtudiant <= 0) {
            header('Location: /etudiants');
            exit();
        }

        $etudiant = $this->adminActions->getEtudiantByIdAndPilote($idEtudiant, $idPilote);
        if (!$etudiant) {
            header('Location: /etudiants');
            exit();
        }

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $prenom = trim((string) ($_POST['prenom'] ?? ''));

            if (!$email || !$nom || !$prenom) {
                $message = 'Tous les champs sont obligatoires.';
            } else {
                if ($this->adminActions->updateEtudiant($idEtudiant, $email, $nom, $prenom, $idPilote)) {
                    header('Location: /etudiants?manage=updated');
                    exit();
                } else {
                    $message = 'Erreur lors de la mise a jour de l etudiant.';
                }
            }
        }

        $this->render('pilote_modifier_etudiant.twig.html', [
            'etudiant' => $etudiant,
            'message' => $message,
        ]);
    }

    public function supprimerEtudiant($idPilote) {
        $idEtudiant = (int) ($_GET['id'] ?? 0);
        if ($idEtudiant <= 0) {
            header('Location: /etudiants');
            exit();
        }

        $etudiant = $this->adminActions->getEtudiantByIdAndPilote($idEtudiant, $idPilote);
        if (!$etudiant) {
            header('Location: /etudiants');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->adminActions->deleteEtudiant($idEtudiant)) {
                header('Location: /etudiants?manage=deleted');
                exit();
            }
        }

        $this->render('pilote_supprimer_etudiant.twig.html', [
            'etudiant' => $etudiant,
        ]);
    }
}
