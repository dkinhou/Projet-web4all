<?php
require_once 'Controller.php';

class controllerDeconnexion extends Controller {
    
    public function __construct($url) {
        parent::__construct();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header('Location: /connexion');
        exit;
    }
}