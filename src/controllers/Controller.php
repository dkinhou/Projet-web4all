<?php

abstract class Controller {
    protected $_twig;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../templates_view');
        $this->_twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true
        ]);
    }

    protected function render($view, $data = []) {
        $data['session'] = $_SESSION;
        echo $this->_twig->render($view, $data);
    }

    // Cette méthode est disponible dans TOUS les controllers
    // car ils héritent tous de Controller.php
    protected function checkRole(array $rolesAutorises): bool {
        // Vérification 1 : est-ce que l'utilisateur est connecté ?
        if (empty($_SESSION['user_id'])) {
            header('Location: /connexion');
            exit;
        }

        // Vérification 2 : est-ce que son rôle est autorisé ?
        if (!in_array($_SESSION['user_role'], $rolesAutorises)) {
            $this->render('viewError.twig.html', [
                'message' => 'Vous n\'avez pas accès à cette page.'
            ]);
            return false;
        }

        return true;
    }
}
