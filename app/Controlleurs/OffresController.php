<?php
//Le contrôleur fait le lien entre le modèle et la vue 
//Il va recevoir les requ^tes, récupérer les données et les envoyer à Twig 
class OffresController{
	private $twig;
	public function __construct(){
		//Configuration de Twig pour qu'il cherche les vues dans app/Vues
		$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../Vues');
		$this->twig = new \Twig\Environment($loader, [
			'cache' => false, 
			'debug' => true
		]);
	}
	public function index(){
		//Chargement du modèle pour récupérer les données 
		require_once __DIR__ . '/../Modeles/OffresModel.php';
		$model = new OffresModel();
		//On récupère toutes les offres 
		$offres = $model->getOffres();
		//Paramètres de pagination 
		$offres_par_page = 2;
		//Page actuelle depuis l'URL 
		$page_actuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		//Calcul du nombre total de pages 
		$total_offres = count($offres);
		$total_pages = ceil($total_offres / $offres_par_page);
		//Découpage du tableau pour n'avoir que les offres de la page actuelle
		$debut = ($page_actuelle - 1) * $offres_par_page; 
		$offres_page = array_slice($offres, $debut, $offres_par_page);
		//On envoie les données à la vue Twig 
		echo $this->twig->render('offres.twig',[
			'offres' => $offres_page, 
			'total_offres' => $total_offres, 
			'page_actuelle' => $page_actuelle, 
			'total_pages' => $total_pages 
		]);
	}
}
