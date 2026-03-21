<?php

abstract class Controller {
    protected $_twig;

    public function __construct() {
       
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../templates_view');
        $this->_twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true
        ]);
    }

    
    protected function render($view, $data = []) {
        echo $this->_twig->render($view, $data);
    }
}