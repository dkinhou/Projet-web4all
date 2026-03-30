<?php
require_once 'Controller.php';
require_once __DIR__ . '/../Model/userconnexion.php';

class controllerConnexion extends Controller {

    public function __construct($url) {
        parent::__construct();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            $this->render('connexion.twig.html', []);
        }
    }

    private function handleLogin(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $this->render('connexion.twig.html', [
                'erreur' => 'Veuillez remplir tous les champs.'
            ]);
            return;
        }

        $userModel = new UserConnexion();
        $user = $userModel->login($email, $password);

        if (!$user) {
            $this->render('connexion.twig.html', [
                'erreur' => 'Email ou mot de passe incorrect.'
            ]);
            return;
        }

        $_SESSION['user_id']     = $user['id'];
        $_SESSION['user_nom']    = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['user_role']   = $user['role'];

        header('Location: /offres');
        exit;
    }
}
