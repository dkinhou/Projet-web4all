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

        public function login($email, $password) {
        $db = new ConnexionDB();
        $conn = $db->getConnection();
        $userConnexion = new UserConnexion($conn);
        $result = $userConnexion->login($email, $password);
        return $result;
        }

}