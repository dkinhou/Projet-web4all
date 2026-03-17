<?php

abstract class Controller {
    protected $_twig;

    public function __construct() {
        // On configure Twig une seule fois ici
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../templates_view');
        $this->_twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true
        ]);
    }

    // Méthode d'aide pour afficher une vue
    protected function render($view, $data = []) {
        echo $this->_twig->render($view, $data);
    }
}