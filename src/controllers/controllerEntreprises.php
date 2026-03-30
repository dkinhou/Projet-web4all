<?php

require_once 'Controller.php';
require_once __DIR__ . '/../Model/entreprises.php';

use App\Model\entreprises;

class controllerEntreprises extends Controller
{
    private $entreprisesModel;
    private $itemsPerPage = 10;

    public function __construct($url)
    {
        parent::__construct();
        $this->entreprisesModel = new entreprises();
    }

    public function index()
    {
        $filters = [
            'keyword' => trim((string) ($_GET['keyword'] ?? '')),
            'secteur' => trim((string) ($_GET['secteur'] ?? '')),
        ];

        $hasFilters = false;
        foreach ($filters as $value) {
            if ($value !== '') {
                $hasFilters = true;
                break;
            }
        }

        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $offset = ($currentPage - 1) * $this->itemsPerPage;

        if ($hasFilters) {
            $totalCount = $this->entreprisesModel->countFilteredEntreprises($filters);
            $entreprises = $this->entreprisesModel->getFilteredEntreprisesPaginated($filters, $this->itemsPerPage, $offset);
        } else {
            $totalCount = $this->entreprisesModel->countAllEntreprises();
            $entreprises = $this->entreprisesModel->getEntreprisesPaginated($this->itemsPerPage, $offset);
        }

        $queryParams = [];
        foreach ($filters as $key => $value) {
            if ($value !== '') {
                $queryParams[$key] = $value;
            }
        }
        $querySuffix = empty($queryParams) ? '' : '&' . http_build_query($queryParams);

        $this->render('entreprises.twig.html', [
            'entreprises' => $entreprises,
            'compteur' => $totalCount,
            'currentPage' => $currentPage,
            'totalPages' => max(1, (int) ceil($totalCount / $this->itemsPerPage)),
            'filters' => $filters,
            'querySuffix' => $querySuffix,
        ]);
    }
}
