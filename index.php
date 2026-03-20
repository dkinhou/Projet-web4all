<?php
//C'est le point d'entrée de l'application 
//On va charger l'autoloader de Composer 
require_once __DIR__ . '/vendor/autoload.php';

//Ensuite on va récupérer l'URL demandée 
$url = $_SERVER['REQUEST_URI'];
//Ensuite on va supprimer le chemin de base si nécessaire
$url = parse_url($url, PHP_URL_PATH);
$url = trim($url, '/');

//Notre routeur va rediriger les requêtes ver le bon contrôleur 
switch($url){
	case '':
	case 'index':
		//Pour la page d'accueil
		require_once __DIR__ . '/app/Controlleurs/AccueilController.php';
		$controller = new AccueilController();
		$controller->index();
		break;

	case 'offres':
		//Pour la page des offres
		require_once __DIR__ . '/app/Controlleurs/OffresController.php';
		$controller = new OffresController();
		$controller->index();
		break;
	default:
		//Pour la page d'errereur 404
		http_response_code(404);
		echo "Page non trouvée";
		break;
}
?>
