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

        $entrepriseIds = [];
        foreach ($entreprises as $entreprise) {
            if (isset($entreprise['id_entreprise'])) {
                $entrepriseIds[] = (int) $entreprise['id_entreprise'];
            }
        }
        $recentEvaluations = $this->entreprisesModel->getRecentEvaluationsByEntrepriseIds($entrepriseIds, 3);

        $queryParams = [];
        foreach ($filters as $key => $value) {
            if ($value !== '') {
                $queryParams[$key] = $value;
            }
        }
        $querySuffix = empty($queryParams) ? '' : '&' . http_build_query($queryParams);

        $message = '';
        if (isset($_GET['created'])) {
            $message = 'Entreprise creee avec succes.';
        } elseif (isset($_GET['updated'])) {
            $message = 'Entreprise modifiee avec succes.';
        } elseif (isset($_GET['deleted'])) {
            $message = 'Entreprise supprimee avec succes.';
        } elseif (isset($_GET['error']) && $_GET['error'] === 'delete') {
            $message = 'Impossible de supprimer cette entreprise (donnees liees).';
        }

        $this->render('entreprises.twig.html', [
            'entreprises' => $entreprises,
            'compteur' => $totalCount,
            'currentPage' => $currentPage,
            'totalPages' => max(1, (int) ceil($totalCount / $this->itemsPerPage)),
            'filters' => $filters,
            'querySuffix' => $querySuffix,
            'message' => $message,
            'canManage' => isset($_SESSION['role']) && in_array($_SESSION['role'], ['Administrateur', 'Pilote'], true),
            'dashboardUrl' => (isset($_SESSION['role']) && $_SESSION['role'] === 'Administrateur') ? '/dashboard-admin' : '/dashboard-pilote',
            'recentEvaluations' => $recentEvaluations,
        ]);
    }

    public function create()
    {
        $formData = [
            'nom_societe' => '',
            'secteur' => '',
            'description' => '',
            'contact' => '',
        ];
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = [
                'nom_societe' => trim((string) ($_POST['nom_societe'] ?? '')),
                'secteur' => trim((string) ($_POST['secteur'] ?? '')),
                'description' => trim((string) ($_POST['description'] ?? '')),
                'contact' => trim((string) ($_POST['contact'] ?? '')),
            ];

            if ($formData['nom_societe'] === '' || $formData['contact'] === '') {
                $message = 'Le nom de la societe et le contact sont obligatoires.';
            } else {
                if ($this->entreprisesModel->createEntreprise(
                    $formData['nom_societe'],
                    $formData['secteur'],
                    $formData['description'],
                    $formData['contact']
                )) {
                    header('Location: /entreprises?created=1');
                    exit();
                }

                $message = 'Erreur lors de la creation de l entreprise.';
            }
        }

        $this->render('entreprise_form.twig.html', [
            'mode' => 'create',
            'message' => $message,
            'formData' => $formData,
            'dashboardUrl' => (isset($_SESSION['role']) && $_SESSION['role'] === 'Administrateur') ? '/dashboard-admin' : '/dashboard-pilote',
        ]);
    }

    public function edit()
    {
        $idEntreprise = (int) ($_GET['id'] ?? $_POST['id_entreprise'] ?? 0);
        if ($idEntreprise <= 0) {
            header('Location: /entreprises');
            exit();
        }

        $entreprise = $this->entreprisesModel->getEntrepriseById($idEntreprise);
        if (!$entreprise) {
            header('Location: /entreprises');
            exit();
        }

        $message = '';
        $formData = [
            'id_entreprise' => $idEntreprise,
            'nom_societe' => $entreprise['nom_societe'] ?? '',
            'secteur' => $entreprise['secteur'] ?? '',
            'description' => $entreprise['description'] ?? '',
            'contact' => $entreprise['contact'] ?? '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData['nom_societe'] = trim((string) ($_POST['nom_societe'] ?? ''));
            $formData['secteur'] = trim((string) ($_POST['secteur'] ?? ''));
            $formData['description'] = trim((string) ($_POST['description'] ?? ''));
            $formData['contact'] = trim((string) ($_POST['contact'] ?? ''));

            if ($formData['nom_societe'] === '' || $formData['contact'] === '') {
                $message = 'Le nom de la societe et le contact sont obligatoires.';
            } else {
                if ($this->entreprisesModel->updateEntreprise(
                    $idEntreprise,
                    $formData['nom_societe'],
                    $formData['secteur'],
                    $formData['description'],
                    $formData['contact']
                )) {
                    header('Location: /entreprises?updated=1');
                    exit();
                }

                $message = 'Erreur lors de la modification de l entreprise.';
            }
        }

        $this->render('entreprise_form.twig.html', [
            'mode' => 'edit',
            'message' => $message,
            'formData' => $formData,
            'dashboardUrl' => (isset($_SESSION['role']) && $_SESSION['role'] === 'Administrateur') ? '/dashboard-admin' : '/dashboard-pilote',
        ]);
    }

    public function delete()
    {
        $idEntreprise = (int) ($_GET['id'] ?? $_POST['id_entreprise'] ?? 0);
        if ($idEntreprise <= 0) {
            header('Location: /entreprises');
            exit();
        }

        $entreprise = $this->entreprisesModel->getEntrepriseById($idEntreprise);
        if (!$entreprise) {
            header('Location: /entreprises');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->entreprisesModel->deleteEntreprise($idEntreprise)) {
                header('Location: /entreprises?deleted=1');
                exit();
            }

            header('Location: /entreprises?error=delete');
            exit();
        }

        $this->render('entreprise_delete.twig.html', [
            'entreprise' => $entreprise,
            'dashboardUrl' => (isset($_SESSION['role']) && $_SESSION['role'] === 'Administrateur') ? '/dashboard-admin' : '/dashboard-pilote',
        ]);
    }
}
