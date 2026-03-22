<?php
require_once 'Controller.php'; 
require_once __DIR__ . '/../Model/userconnexion.php';

class controllerConnexion extends Controller {
    
    public function __construct($url) {
        parent::__construct(); 
    }

    public function index() {
        $this->render('connexion.twig.html', [
            'message' => 'Bienvenue !'
        ]);
    }

    public function indexetudiant(&$email) {
        $this->render('connexion_etudiant.twig.html', [
            'message' => 'Bienvenue !'
        ]);
    }

    public function indexadmin(&$email) {
        $this->render('connexion_admin.twig.html', [
            'message' => 'Bienvenue Admin !'
        ]);
    }

    public function indexpilote(&$email) {
        $this->render('connexion_pilote.twig.html', [
           'Utilisateur' => $this->getuserprenom($email),
        ]);
    }

    public function getuserprenom($email) {
        $db = new ConnexionDB();
        $conn = $db->getConnection();
        $userConnexion = new UserConnexion($conn);
        $prenom = $userConnexion->getuserprenom($email);
        return $prenom;
        }

        public function login($email, $password) {
        $db = new ConnexionDB();
        $conn = $db->getConnection();
        $userConnexion = new UserConnexion($conn);
        $result = $userConnexion->login($email, $password);
        return $result;
        }

        public function getuserrole($email) {
        $db = new ConnexionDB();
        $conn = $db->getConnection();
        $userConnexion = new UserConnexion($conn);
        $role = $userConnexion->getuserrole($email);
        return $role;
        }

}