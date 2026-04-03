<?php
use App\Model\AdminActions;
use App\Model\offres;
use App\Model\entreprises;

require_once 'Controller.php'; 
require_once __DIR__ . '/../Model/AdminActions.php';
require_once __DIR__ . '/../Model/offres.php';
require_once __DIR__ . '/../Model/entreprises.php';

class controllerAdmin extends Controller {
    private $adminActions;
    private $offresModel;
    private $entreprisesModel;

    public function __construct() {
        parent::__construct();
        $this->adminActions = new AdminActions();
        $this->offresModel = new offres();
        $this->entreprisesModel = new entreprises();
    }

    // ==================== GESTION DES PILOTES ====================
    
    public function listePilotes() {
        // Liste admin paginee des pilotes existants.
        $itemsPerPage = 15;
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;

        $offset = ($currentPage - 1) * $itemsPerPage;
        $totalCount = $this->adminActions->countPilotes();
        $pilotes = $this->adminActions->getAllPilotes($itemsPerPage, $offset);

        $this->render('admin_pilotes.twig.html', [
            'pilotes' => $pilotes,
            'compteur' => $totalCount,
            'currentPage' => $currentPage,
            'totalPages' => max(1, (int) ceil($totalCount / $itemsPerPage)),
        ]);
    }

    public function creerPilote() {
        // Formulaire de creation d'un pilote avec validation minimale.
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
                if ($this->adminActions->createPilote($email, $password, $nom, $prenom)) {
                    header('Location: /admin-pilotes?created=1');
                    exit();
                } else {
                    $message = 'Erreur lors de la creation du pilote. Verifiez que l email n existe pas deja.';
                    $formData = ['email' => $email, 'nom' => $nom, 'prenom' => $prenom, 'password' => ''];
                }
            }
        }

        $this->render('admin_creer_pilote.twig.html', [
            'message' => $message,
            'formData' => $formData,
        ]);
    }

    public function modifierPilote() {
        // Edition d'un pilote existant apres verification de l'identifiant.
        $idPilote = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($idPilote <= 0) {
            header('Location: /admin-pilotes');
            exit();
        }

        $pilote = $this->adminActions->getPiloteById($idPilote);
        if (!$pilote) {
            header('Location: /admin-pilotes');
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
                if ($this->adminActions->updatePilote($idPilote, $email, $nom, $prenom)) {
                    header('Location: /admin-pilotes?updated=1');
                    exit();
                } else {
                    $message = 'Erreur lors de la mise a jour du pilote.';
                }
            }
        }

        $this->render('admin_modifier_pilote.twig.html', [
            'pilote' => $pilote,
            'message' => $message,
        ]);
    }

    public function supprimerPilote() {
        // Ecran de confirmation avant suppression definitive d'un pilote.
        $idPilote = (int) ($_GET['id'] ?? 0);
        if ($idPilote <= 0) {
            header('Location: /admin-pilotes');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->adminActions->deletePilote($idPilote)) {
                header('Location: /admin-pilotes?deleted=1');
                exit();
            }
        }

        $pilote = $this->adminActions->getPiloteById($idPilote);
        if (!$pilote) {
            header('Location: /admin-pilotes');
            exit();
        }

        $this->render('admin_supprimer_pilote.twig.html', [
            'pilote' => $pilote,
        ]);
    }

    // ==================== GESTION DES ETUDIANTS ====================
    
    public function listeEtudiants() {
        // Liste admin paginee des etudiants.
        $itemsPerPage = 15;
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;

        $offset = ($currentPage - 1) * $itemsPerPage;
        $totalCount = $this->adminActions->countEtudiants();
        $etudiants = $this->adminActions->getAllEtudiants($itemsPerPage, $offset);

        $this->render('admin_etudiants.twig.html', [
            'etudiants' => $etudiants,
            'compteur' => $totalCount,
            'currentPage' => $currentPage,
            'totalPages' => max(1, (int) ceil($totalCount / $itemsPerPage)),
        ]);
    }

    public function creerEtudiant() {
        // Creation d'un etudiant avec affectation obligatoire a un pilote.
        $message = '';
        $formData = ['email' => '', 'nom' => '', 'prenom' => '', 'password' => '', 'id_pilote' => ''];
        $pilotes = [];

        $adminActions = new AdminActions();
        $pilotes = $adminActions->getAllPilotes(999, 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $prenom = trim((string) ($_POST['prenom'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $idPilote = !empty($_POST['id_pilote']) ? (int) $_POST['id_pilote'] : null;

            if (!$email || !$nom || !$prenom || !$password) {
                $message = 'Tous les champs sont obligatoires.';
            } elseif ($idPilote === null) {
                $message = 'La selection d un pilote est obligatoire pour un etudiant.';
            } elseif (strlen($password) < 6) {
                $message = 'Le mot de passe doit contenir au moins 6 caracteres.';
            } else {
                if ($this->adminActions->createEtudiant($email, $password, $nom, $prenom, $idPilote)) {
                    header('Location: /admin-etudiants?created=1');
                    exit();
                } else {
                    $message = 'Erreur lors de la creation de l etudiant. Verifiez que l email n existe pas deja.';
                    $formData = ['email' => $email, 'nom' => $nom, 'prenom' => $prenom, 'password' => '', 'id_pilote' => $idPilote];
                }
            }
        }

        $this->render('admin_creer_etudiant.twig.html', [
            'message' => $message,
            'formData' => $formData,
            'pilotes' => $pilotes,
        ]);
    }

    public function modifierEtudiant() {
        // Mise a jour d'un etudiant existant et de son pilote attribue.
        $idEtudiant = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($idEtudiant <= 0) {
            header('Location: /admin-etudiants');
            exit();
        }

        $etudiant = $this->adminActions->getEtudiantById($idEtudiant);
        if (!$etudiant) {
            header('Location: /admin-etudiants');
            exit();
        }

        $message = '';
        $adminActions = new AdminActions();
        $pilotes = $adminActions->getAllPilotes(999, 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $nom = trim((string) ($_POST['nom'] ?? ''));
            $prenom = trim((string) ($_POST['prenom'] ?? ''));
            $idPilote = !empty($_POST['id_pilote']) ? (int) $_POST['id_pilote'] : null;

            if (!$email || !$nom || !$prenom) {
                $message = 'Tous les champs sont obligatoires.';
            } elseif ($idPilote === null) {
                $message = 'La selection d un pilote est obligatoire pour un etudiant.';
            } else {
                if ($this->adminActions->updateEtudiant($idEtudiant, $email, $nom, $prenom, $idPilote)) {
                    header('Location: /admin-etudiants?updated=1');
                    exit();
                } else {
                    $message = 'Erreur lors de la mise a jour de l etudiant.';
                }
            }
        }

        $this->render('admin_modifier_etudiant.twig.html', [
            'etudiant' => $etudiant,
            'pilotes' => $pilotes,
            'message' => $message,
        ]);
    }

    public function supprimerEtudiant() {
        // Confirmation avant suppression d'un etudiant et de ses relations.
        $idEtudiant = (int) ($_GET['id'] ?? 0);
        if ($idEtudiant <= 0) {
            header('Location: /admin-etudiants');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->adminActions->deleteEtudiant($idEtudiant)) {
                header('Location: /admin-etudiants?deleted=1');
                exit();
            }
        }

        $etudiant = $this->adminActions->getEtudiantById($idEtudiant);
        if (!$etudiant) {
            header('Location: /admin-etudiants');
            exit();
        }

        $this->render('admin_supprimer_etudiant.twig.html', [
            'etudiant' => $etudiant,
        ]);
    }
}
