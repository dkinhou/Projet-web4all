<?php
require_once 'Controller.php'; // On inclut le parent

class controllerConnexion extends Controller {
    
    public function __construct($url) {
        parent::__construct(); // On appelle le constructeur du parent (pour Twig)
        $this->index();
    }

    public function index() {
        // On utilise la méthode 'render' héritée du parent
        $this->render('connexion.twig.html', [
            'message' => 'Bienvenue !'
        ]);
    }
}