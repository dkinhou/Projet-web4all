<?php
require_once 'Controller.php'; 

class controllerOffres extends Controller {
    
    public function __construct($url) {
        parent::__construct();
        $this->index();
    }

    public function index() {

        $this->render('offres.twig.html', [
            'message' => 'Bienvenue !'
        ]);
    }
}