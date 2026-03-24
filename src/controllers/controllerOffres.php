<?php
require_once 'Controller.php'; 
require_once __DIR__ . '/../Model/offres.php';

use App\Model\offres;

class controllerOffres extends Controller {
    private $offresModel;
    private $offresPerPage = 12; 
    private $currentPage ;

    public function __construct($url) {
        parent::__construct();
        $this->offresModel = new offres();
    }

    public function index() {
        $taboffres = $this->offresModel->getAllOffres();
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $this->offresPerPage;
        $paginatedOffres = $this->offresModel->getOffresPaginated($this->offresPerPage, $offset);
            $this->render('offres.twig.html', [
                'offres' => $paginatedOffres,
                'compteur' => count($taboffres),
                'currentPage' => $currentPage,
                'totalPages' => ceil(count($taboffres) / $this->offresPerPage)
            ]);
    }

}