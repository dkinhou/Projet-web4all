<?php 
require_once 'Controller.php';
require_once __DIR__ . '/../Model/DetailOffre.php';
require_once __DIR__ . '/../Model/offres.php';
use App\Model\offres;
use App\Model\DetailOffre;
class controllerDetailOffres extends controller
{
    public function __construct($url)
    {
        parent::__construct($url);
    }

    public function index()
    {
        $detailOffreModel = new DetailOffre();
        $offresModel = new offres();
        $offresId = $offresModel->getOffresId();
        $id_offre = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id_offre) {
            $offreDetails = $detailOffreModel->getOffreDetails($id_offre);
            if ($offreDetails) {
                $this->render('detail_offres.twig.html', [
                    'offre' => $offreDetails
                ]);
            } else {
                echo "Offre non trouvée.";
            }
        } else {
            echo "ID de l'offre manquant.";
        }

    }

}