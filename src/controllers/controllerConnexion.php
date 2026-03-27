<?php
use App\Model\UserConnexion;
use App\Model\ConnexionDB;

require_once 'Controller.php'; 
require_once __DIR__ . '/../Model/UserConnexion.php';


class controllerConnexion extends Controller {
    private $user;
    
    public function __construct($url) {
        parent::__construct(); 
        $db = new ConnexionDB();
        $conn = $db->getConnection();
        $this->user = new UserConnexion($conn);
    }

    public function index() {
        $this->render('connexion.twig.html', [
            'message' => 'Bienvenue !'
        ]);
    }

    public function indexetudiant(&$email) {
        $this->render('connexion_etudiant.twig.html', [
            'message' => 'Bienvenue !',
            'Utilisateur' => $this->user->getuserprenom($email),
        ]);
    }

    public function indexadmin(&$email) {
        $this->render('connexion_admin.twig.html', [
            'message' => 'Bienvenue Admin !'
        ]);
    }

    public function indexpilote(&$email) {
        $this->render('connexion_pilote.twig.html', [
           'Utilisateur' => $this->user->getuserprenom($email),
        ]);
    }

        public function login($email, $password) {
        $result = $this->user->login($email, $password);
        return $result;
        }

        public function getuserrole($email) {
        $role = $this->user->getuserrole($email);
        return $role;
        }

        public function getId($email) {
        $id = $this->user->getIdByEmail($email);
        return $id; 
        }

}