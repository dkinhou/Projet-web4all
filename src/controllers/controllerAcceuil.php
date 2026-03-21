<?php
require_once 'Controller.php'; 

class controllerAcceuil extends Controller {
    
    public function __construct($url) {
        parent::__construct(); 
    }

    public function index() {
        
        $this->render('acceuil.twig.html', [
            'message' => 'Bienvenue !'
        ]);
    }
}