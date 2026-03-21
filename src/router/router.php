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
                        if ($this->_ctrl->login($email, $password)) {
                            echo "<script> alert('Connexion réussie à la base de données'); </script>";
                                                  
                        } else {
                            echo "<script> alert('Échec de la connexion à la base de données'); </script>";
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