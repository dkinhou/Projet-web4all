<?php

class controllerAcceuil 
{
    private $_twig;

    public function __construct($url)
    {
        // 1. Initialiser Twig (indispensable dans chaque contrôleur qui affiche une vue)
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../templates_view');
        $this->_twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true
        ]);

        // 2. Lancer l'affichage
        $this->index();
    }

    public function index()
    {
        // Tu peux passer des données à la vue ici
        echo $this->_twig->render('index.twig.html', [
            'titre' => 'Bienvenue sur StageAlternance'
        ]);
    }
}