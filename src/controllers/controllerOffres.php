<?php
require_once 'Controller.php'; 
require_once __DIR__ . '/../Model/offres.php';

use App\Model\offres;

class controllerOffres extends Controller {
    private $offresModel;
    private $offresPerPage = 10; 
    private $currentPage ;

    public function __construct($url) {
        parent::__construct();
        $this->offresModel = new offres();
    }

    public function index() {
        $isPilot = isset($_SESSION['role']) && $_SESSION['role'] === 'Pilote';

        if ($isPilot && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $actionType = $_POST['action_type'] ?? '';
            $offreId = isset($_POST['id_offre']) ? (int) $_POST['id_offre'] : 0;

            if ($offreId > 0 && $actionType === 'delete') {
                $result = $this->offresModel->deleteOffre($offreId);
                header('Location: /offres?manage=' . ($result ? 'deleted' : 'error'));
                exit();
            }

            if ($offreId > 0 && $actionType === 'update') {
                $titre = trim((string) ($_POST['titre'] ?? ''));
                $description = trim((string) ($_POST['description'] ?? ''));
                $ville = trim((string) ($_POST['ville'] ?? ''));

                if ($titre === '' || $description === '' || $ville === '') {
                    header('Location: /offres?edit=' . $offreId . '&manage=invalid');
                    exit();
                }

                $payload = [
                    'titre' => $titre,
                    'description' => $description,
                    'ville' => $ville,
                    'type_contrat' => ($_POST['type_contrat'] ?? 'Stage') === 'Alternance' ? 'Alternance' : 'Stage',
                    'duree' => trim((string) ($_POST['duree'] ?? '')),
                    'remuneration' => trim((string) ($_POST['remuneration'] ?? '')),
                ];

                $result = $this->offresModel->updateOffre($offreId, $payload);
                header('Location: /offres?manage=' . ($result ? 'updated' : 'error'));
                exit();
            }
        }

        $filters = [
            'keyword' => trim((string) ($_GET['keyword'] ?? '')),
            'location' => trim((string) ($_GET['location'] ?? '')),
            'entreprise' => trim((string) ($_GET['entreprise'] ?? '')),
            'specialite' => trim((string) ($_GET['specialite'] ?? '')),
            'niveau' => trim((string) ($_GET['niveau'] ?? '')),
            'type_contrat' => trim((string) ($_GET['type_contrat'] ?? $_GET['type'] ?? '')),
        ];

        $hasFilters = false;
        foreach ($filters as $value) {
            if ($value !== '') {
                $hasFilters = true;
                break;
            }
        }

        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        $offset = ($currentPage - 1) * $this->offresPerPage;

        if ($hasFilters) {
            $totalCount = $this->offresModel->countOffresFiltered($filters);
            $paginatedOffres = $this->offresModel->getOffresFilteredPaginated($filters, $this->offresPerPage, $offset);
        } else {
            $taboffres = $this->offresModel->getAllOffres();
            $totalCount = count($taboffres);
            $paginatedOffres = $this->offresModel->getOffresPaginated($this->offresPerPage, $offset);
        }

        $editOffer = null;
        if ($isPilot && isset($_GET['edit'])) {
            $editOffer = $this->offresModel->getOffreById((int) $_GET['edit']);
        }

        $manage = $_GET['manage'] ?? '';
        $manageMessage = '';
        if ($manage === 'updated') {
            $manageMessage = 'Offre modifiee avec succes.';
        } elseif ($manage === 'deleted') {
            $manageMessage = 'Offre supprimee avec succes.';
        } elseif ($manage === 'invalid') {
            $manageMessage = 'Veuillez renseigner titre, description et ville.';
        } elseif ($manage === 'error') {
            $manageMessage = 'Une erreur est survenue pendant la mise a jour.';
        }

        $queryParams = [];
        foreach ($filters as $key => $value) {
            if ($value !== '') {
                $queryParams[$key] = $value;
            }
        }
        $querySuffix = empty($queryParams) ? '' : '&' . http_build_query($queryParams);

            $this->render('offres.twig.html', [
                'offres' => $paginatedOffres,
                'compteur' => $totalCount,
                'currentPage' => $currentPage,
                'totalPages' => max(1, (int) ceil($totalCount / $this->offresPerPage)),
                'editOffer' => $editOffer,
                'manageMessage' => $manageMessage,
                'filters' => $filters,
                'querySuffix' => $querySuffix
            ]);
    }

}