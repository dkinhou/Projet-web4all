<?php
require_once 'Controller.php'; 
require_once __DIR__ . '/../Model/offres.php';

use App\Model\offres;

class controllerOffres extends Controller {
    private $offresModel;

    public function __construct($url) {
        parent::__construct();
        $this->offresModel = new offres();
    }

    public function index() {

    $taboffres = $this->offresModel->getAllOffres();
        $this->render('offres.twig.html', [
            'offres' => $taboffres,
            'compteur' => count($taboffres)
        ]);
    }

}