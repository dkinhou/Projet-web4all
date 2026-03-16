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
                
                // On s'assure que le nom correspond à tes fichiers (ex: controllerAcceuil)
                $controller = ucfirst(strtolower($url[0]));
                $controllerClass = 'controller' . $controller;
                
                // Chemin corrigé vers src/controllers/
                $controllerFile = __DIR__ . '/../controllers/' . $controllerClass . '.php';

                if (file_exists($controllerFile)) 
                {
                    require_once($controllerFile);
                    $this->_ctrl = new $controllerClass($url);
                } 
                else 
                {
                    throw new Exception("Page introuvable");
                }
            }
            else
            {   
                // Page par défaut (Accueil)
                require_once(__DIR__ . '/../controllers/controllerAcceuil.php');
                $this->_ctrl = new controllerAcceuil($url);
            }
        }
        catch (Exception $e) 
        {
            $errorMsg = $e->getMessage();
            // Au lieu d'un require, il faudrait appeler une méthode Twig ici
            echo "Erreur : " . $errorMsg; 
        }
    }
}