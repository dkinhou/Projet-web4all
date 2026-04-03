<?php 
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

class router 
{
    private $_ctrl;


    private function isConnected() {
    return isset($_SESSION['id']);
    }

    private function getDashboardByRole($role)
    {
        if ($role === 'Etudiant') {
            return '/dashboard-etudiant';
        }
        if ($role === 'Pilote') {
            return '/dashboard-pilote';
        }
        if ($role === 'Administrateur') {
            return '/dashboard-admin';
        }

        return '/connexion';
    }


    public function routeReq()
    {
        try 
        {
            // Dispatcher principal: chaque URL est envoyee vers le bon controleur.
            $url = '';
            if (isset($_GET['url']) && !empty($_GET['url']))
            {   
                $url = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));

                // Routes publiques accessibles sans session.
                if ($url[0] === 'acceuil') 
                {
                    require_once(__DIR__ . '/../controllers/controllerAcceuil.php');
                    $this->_ctrl = new controllerAcceuil($url);
                    $this->_ctrl->index();
                }
                else if ($url[0] === 'connexion') 
                {
                    if ($this->isConnected() && isset($_SESSION['role'])) {
                        header('Location: ' . $this->getDashboardByRole($_SESSION['role']));
                        exit();
                    }
                    require_once(__DIR__ . '/../controllers/controllerConnexion.php');
                    $this->_ctrl = new controllerConnexion($url);
                    $this->_ctrl->index();
                }
                else if ($url[0] === 'login') 
                {
                    require_once(__DIR__ . '/../controllers/controllerConnexion.php');
                    $this->_ctrl = new controllerConnexion($url);
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                        $loginResult = $this->_ctrl->login($email, $password);
                        if ($loginResult) {
                        $_SESSION['email'] = $email;
                        $_SESSION['id'] = $this->_ctrl->getId($email);
                        $_SESSION['role'] = $this->_ctrl->getuserrole($email);
                        header('Location: ' . $this->getDashboardByRole($_SESSION['role']));
                        exit();
                        }
                        else {
                            echo "<script> alert('Email ou mot de passe incorrect'); </script>";
                            $this->_ctrl->index();
                        }
                    }
                }
                else if ($url[0] === 'dashboard-etudiant')
                {
                    // Routes protegees: on verifie le role avant d'afficher le tableau de bord.
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Etudiant') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerConnexion.php');
                    $this->_ctrl = new controllerConnexion($url);
                    $email = $_SESSION['email'];
                    $this->_ctrl->indexetudiant($email);
                }
                else if ($url[0] === 'dashboard-pilote')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Pilote') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerConnexion.php');
                    $this->_ctrl = new controllerConnexion($url);
                    $email = $_SESSION['email'];
                    $this->_ctrl->indexpilote($email);
                }
                else if ($url[0] === 'dashboard-admin')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerConnexion.php');
                    $this->_ctrl = new controllerConnexion($url);
                    $email = $_SESSION['email'];
                    $this->_ctrl->indexadmin($email);
                }
                else if ($url[0] === 'postuler')
                {
                    // Actions POST directes pour un etudiant connecte.
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Etudiant') {
                        header('Location: /connexion');
                        exit();
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $offreId = isset($_POST['id_offre']) ? (int)$_POST['id_offre'] : 0;
                        if ($offreId > 0) {
                            require_once(__DIR__ . '/../Model/EtudiantActions.php');
                            $etudiantActions = new \App\Model\EtudiantActions();
                            $userId = (int) $_SESSION['id'];
                            if ($etudiantActions->applyToOffer($userId, $offreId)) {
                                header('Location: /detail_offres?id=' . $offreId . '&postulation=success');
                                exit();
                            }
                            header('Location: /detail_offres?id=' . $offreId . '&postulation=already');
                            exit();
                        }
                    }

                    header('Location: /offres');
                    exit();
                }
                else if ($url[0] === 'wishlist')
                {
                    // Gestion de la wishlist sans passer par une vue intermédiaire.
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Etudiant') {
                        header('Location: /connexion');
                        exit();
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $offreId = isset($_POST['id_offre']) ? (int) $_POST['id_offre'] : 0;
                        $action = isset($_POST['action']) ? $_POST['action'] : 'add';

                        if ($offreId > 0) {
                            require_once(__DIR__ . '/../Model/EtudiantActions.php');
                            $etudiantActions = new \App\Model\EtudiantActions();
                            $userId = (int) $_SESSION['id'];

                            if (!$etudiantActions->hasEtudiantProfile($userId)) {
                                header('Location: /detail_offres?id=' . $offreId . '&wishlist=profile_missing');
                                exit();
                            }

                            if ($action === 'remove') {
                                $etudiantActions->removeFromWishlist($userId, $offreId);
                                header('Location: /detail_offres?id=' . $offreId . '&wishlist=removed');
                                exit();
                            }

                            if ($etudiantActions->addToWishlist($userId, $offreId)) {
                                header('Location: /detail_offres?id=' . $offreId . '&wishlist=added');
                                exit();
                            }

                            header('Location: /detail_offres?id=' . $offreId . '&wishlist=already');
                            exit();
                        }
                    }

                    header('Location: /offres');
                    exit();
                }
                else if ($url[0] === 'logout')
                {
                    $_SESSION = [];
                    if (ini_get('session.use_cookies')) {
                        $params = session_get_cookie_params();
                        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
                    }
                    session_destroy();
                    header('Location: /connexion');
                    exit();
                }
                else if ($url[0] === 'detail_offres') 
                {
                    // Page de detail d'offre: affichage + actions de l'offre courante.
                    require_once(__DIR__ . '/../controllers/controllerDetailOffres.php');
                    $this->_ctrl = new controllerDetailOffres($url);
                    $this->_ctrl->index();
                }
                else if ($url[0] === 'inscription')
                {
                    require_once(__DIR__ . '/../controllers/controllerInscription.php');
                    $this->_ctrl = new controllerInscription($url);
                }
                else if ($url[0] === 'offres') 
                {
                    // Liste des offres, filtres et actions de gestion selon le role.
                    require_once(__DIR__ . '/../controllers/controllerOffres.php');
                    $this->_ctrl = new controllerOffres($url);
                    $this->_ctrl->index();
                }
                else if ($url[0] === 'entreprises') 
                {
                    require_once(__DIR__ . '/../controllers/controllerEntreprises.php');
                    $this->_ctrl = new controllerEntreprises($url);
                    $this->_ctrl->index();
                }
                else if ($url[0] === 'contact')
                {
                    require_once(__DIR__ . '/../controllers/controllerContact.php');
                    $this->_ctrl = new controllerContact($url);
                }
                else if ($url[0] === 'mentions')
                {
                    require_once(__DIR__ . '/../controllers/controllerMentions.php');
                    $this->_ctrl = new controllerMentions($url);
                    $this->_ctrl->index();
                }
                else if ($url[0] === 'liste-candidatures-pilote')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Pilote') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerConnexion.php');
                    $this->_ctrl = new controllerConnexion($url);
                    $idPilote = (int) $_SESSION['id'];
                    $this->_ctrl->listeCandidaturesPilote($idPilote);
                }
                else if ($url[0] === 'evaluer-entreprise')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Pilote', 'Administrateur'], true)) {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerConnexion.php');
                    $this->_ctrl = new controllerConnexion($url);
                    $idUtilisateur = (int) $_SESSION['id'];
                    $this->_ctrl->evaluerEntreprise($idUtilisateur);
                }
                else if ($url[0] === 'admin-pilotes')
                {
                    // Espace administrateur: gestion des pilotes et des etudiants.
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerAdmin.php');
                    $this->_ctrl = new controllerAdmin();
                    $this->_ctrl->listePilotes();
                }
                else if ($url[0] === 'admin-creer-pilote')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerAdmin.php');
                    $this->_ctrl = new controllerAdmin();
                    $this->_ctrl->creerPilote();
                }
                else if ($url[0] === 'admin-modifier-pilote')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerAdmin.php');
                    $this->_ctrl = new controllerAdmin();
                    $this->_ctrl->modifierPilote();
                }
                else if ($url[0] === 'admin-supprimer-pilote')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerAdmin.php');
                    $this->_ctrl = new controllerAdmin();
                    $this->_ctrl->supprimerPilote();
                }
                else if ($url[0] === 'admin-etudiants')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerAdmin.php');
                    $this->_ctrl = new controllerAdmin();
                    $this->_ctrl->listeEtudiants();
                }
                else if ($url[0] === 'admin-creer-etudiant')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerAdmin.php');
                    $this->_ctrl = new controllerAdmin();
                    $this->_ctrl->creerEtudiant();
                }
                else if ($url[0] === 'admin-modifier-etudiant')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerAdmin.php');
                    $this->_ctrl = new controllerAdmin();
                    $this->_ctrl->modifierEtudiant();
                }
                else if ($url[0] === 'admin-supprimer-etudiant')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Administrateur') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerAdmin.php');
                    $this->_ctrl = new controllerAdmin();
                    $this->_ctrl->supprimerEtudiant();
                }
                else if ($url[0] === 'creer-entreprise' || $url[0] === 'modifier-entreprise' || $url[0] === 'supprimer-entreprise')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Pilote', 'Administrateur'], true)) {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerEntreprises.php');
                    $this->_ctrl = new controllerEntreprises($url);

                    if ($url[0] === 'creer-entreprise') {
                        $this->_ctrl->create();
                    } else if ($url[0] === 'modifier-entreprise') {
                        $this->_ctrl->edit();
                    } else {
                        $this->_ctrl->delete();
                    }
                }
                else if ($url[0] === 'creer-offre' || $url[0] === 'modifier-offre' || $url[0] === 'supprimer-offre')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Pilote', 'Administrateur'], true)) {
                        header('Location: /connexion');
                        exit();
                    }

                    if ($url[0] === 'creer-offre') {
                        header('Location: /offres?create=1');
                        exit();
                    }

                    if ($url[0] === 'modifier-offre') {
                        header('Location: /offres?mode=edit');
                        exit();
                    }

                    header('Location: /offres?mode=delete');
                    exit();
                }
                else if ($url[0] === 'etudiants')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Pilote') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerPilote.php');
                    $this->_ctrl = new controllerPilote();
                    $idPilote = (int) $_SESSION['id'];
                    $this->_ctrl->listeEtudiants($idPilote);
                }
                else if ($url[0] === 'creer-etudiant')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Pilote') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerPilote.php');
                    $this->_ctrl = new controllerPilote();
                    $idPilote = (int) $_SESSION['id'];
                    $this->_ctrl->creerEtudiant($idPilote);
                }
                else if ($url[0] === 'modifier-etudiant')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Pilote') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerPilote.php');
                    $this->_ctrl = new controllerPilote();
                    $idPilote = (int) $_SESSION['id'];
                    $this->_ctrl->modifierEtudiant($idPilote);
                }
                else if ($url[0] === 'supprimer-etudiant')
                {
                    if (!$this->isConnected() || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Pilote') {
                        header('Location: /connexion');
                        exit();
                    }

                    require_once(__DIR__ . '/../controllers/controllerPilote.php');
                    $this->_ctrl = new controllerPilote();
                    $idPilote = (int) $_SESSION['id'];
                    $this->_ctrl->supprimerEtudiant($idPilote);
                }
                else 
                {
                    throw new Exception("Page introuvable");
                }
            }
            else
            {   
                require_once(__DIR__ . '/../controllers/controllerAcceuil.php');
                $this->_ctrl = new controllerAcceuil($url);
                $this->_ctrl->index();
            }
        }
        catch (Exception $e) 
        {
            $errorMsg = $e->getMessage();
            echo "Erreur : " . $errorMsg; 
        }
    }
}