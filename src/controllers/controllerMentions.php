<?php
require_once 'Controller.php'; 

class controllerMentions extends Controller {
    
    public function __construct($url) {
        parent::__construct(); 
    }

    public function index() {
        
        $this->render('mentions.twig.html', [
            'message' => 'Mentions légales'
        ]);
    }
}