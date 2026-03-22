<?php 
require_once __DIR__ . '/../../vendor/autoload.php';

class router 
{
    private $_ctrl;

    public function routeReq()
    {
        try 
        {
            
            $url = '';
            if (isset($_GET['url']) && !empty($_GET['url']))
            {   
                $url = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));

                if ($url[0] === 'acceuil') 
                {
                    require_once(__DIR__ . '/../controllers/controllerAcceuil.php');
                    $this->_ctrl = new controllerAcceuil($url);
                    $this->_ctrl->index();
                }
                else if ($url[0] === 'connexion') 
                {
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
                        $this->_ctrl->login($email, $password);
                        $role = $this->_ctrl->getuserrole($email);
                        if ($this->_ctrl->login($email, $password) && $role === 'Etudiant') {
                            echo "<script> alert('Connexion réussie pour l'étudiant'); </script>";
                            $this->_ctrl->indexetudiant($email);                   
                        } 
                        else if ($this->_ctrl->login($email, $password) && $role === 'Administrateur') {
                            echo "<script> alert('Connexion réussie pour l'administrateur'); </script>";
                            $this->_ctrl->indexadmin($email);
                        }
                        else if ($this->_ctrl->login($email, $password) && $role === 'Pilote') {
                            echo "<script> alert('Connexion réussie pour le pilote'); </script>";
                            $this->_ctrl->indexpilote($email);
                        }
                        else {
                            echo "<script> alert('Email ou mot de passe incorrect'); </script>";
                            $this->_ctrl->index();
                        }
                    }
                }
                else if ($url[0] === 'inscription')
                {
                    require_once(__DIR__ . '/../controllers/controllerInscription.php');
                    $this->_ctrl = new controllerInscription($url);
                }
                else if ($url[0] === 'offres') 
                {
                    require_once(__DIR__ . '/../controllers/controllerOffres.php');
                    $this->_ctrl = new controllerOffres($url);
                }
                else if ($url[0] === 'entreprises') 
                {
                    require_once(__DIR__ . '/../controllers/controllerEntreprises.php');
                    $this->_ctrl = new controllerEntreprises($url);
                }
                else if ($url[0] === 'contact')
                {
                    require_once(__DIR__ . '/../controllers/controllerContact.php');
                    $this->_ctrl = new controllerContact($url);
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