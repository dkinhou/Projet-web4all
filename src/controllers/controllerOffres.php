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
        // Les pilotes et administrateurs peuvent modifier les offres depuis cette page.
        $canManage = isset($_SESSION['role']) && in_array($_SESSION['role'], ['Pilote', 'Administrateur'], true);

        if ($canManage && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Une seule route gere creation, mise a jour et suppression selon action_type.
            $actionType = $_POST['action_type'] ?? '';
            $offreId = isset($_POST['id_offre']) ? (int) $_POST['id_offre'] : 0;

            if ($actionType === 'create') {
                $titre = trim((string) ($_POST['titre'] ?? ''));
                $description = trim((string) ($_POST['description'] ?? ''));
                $ville = trim((string) ($_POST['ville'] ?? ''));
                $idEntreprise = (int) ($_POST['id_entreprise'] ?? 0);

                if ($titre === '' || $description === '' || $ville === '' || $idEntreprise <= 0) {
                    header('Location: /offres?create=1&manage=invalid');
                    exit();
                }

                $payload = [
                    'titre' => $titre,
                    'description' => $description,
                    'ville' => $ville,
                    'id_entreprise' => $idEntreprise,
                    'type_contrat' => ($_POST['type_contrat'] ?? 'Stage') === 'Alternance' ? 'Alternance' : 'Stage',
                    'duree' => trim((string) ($_POST['duree'] ?? '')),
                    'missions' => trim((string) ($_POST['missions'] ?? '')),
                    'profil_recherche' => trim((string) ($_POST['profil_recherche'] ?? '')),
                    'remuneration' => trim((string) ($_POST['remuneration'] ?? '')),
                    'avantages' => trim((string) ($_POST['avantages'] ?? '')),
                ];

                $result = $this->offresModel->createOffre($payload);
                header('Location: /offres?manage=' . ($result ? 'created' : 'error'));
                exit();
            }

            if ($offreId > 0 && $actionType === 'delete') {
                $result = $this->offresModel->deleteOffre($offreId);
                header('Location: /offres?manage=' . ($result ? 'deleted' : 'error'));
                exit();
            }

            if ($offreId > 0 && $actionType === 'update') {
                $titre = trim((string) ($_POST['titre'] ?? ''));
                $description = trim((string) ($_POST['description'] ?? ''));
                $ville = trim((string) ($_POST['ville'] ?? ''));
                $idEntreprise = (int) ($_POST['id_entreprise'] ?? 0);

                if ($titre === '' || $description === '' || $ville === '' || $idEntreprise <= 0) {
                    header('Location: /offres?edit=' . $offreId . '&manage=invalid');
                    exit();
                }

                $payload = [
                    'titre' => $titre,
                    'description' => $description,
                    'ville' => $ville,
                    'id_entreprise' => $idEntreprise,
                    'type_contrat' => ($_POST['type_contrat'] ?? 'Stage') === 'Alternance' ? 'Alternance' : 'Stage',
                    'duree' => trim((string) ($_POST['duree'] ?? '')),
                    'missions' => trim((string) ($_POST['missions'] ?? '')),
                    'profil_recherche' => trim((string) ($_POST['profil_recherche'] ?? '')),
                    'remuneration' => trim((string) ($_POST['remuneration'] ?? '')),
                ];

                $result = $this->offresModel->updateOffre($offreId, $payload);
                header('Location: /offres?manage=' . ($result ? 'updated' : 'error'));
                exit();
            }
        }

        $filters = [
            // Filtres GET utilises par le formulaire de recherche et la modale.
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
            // Lorsque des criteres sont actifs, on interroge le modele avec pagination et filtres.
            $totalCount = $this->offresModel->countOffresFiltered($filters);
            $paginatedOffres = $this->offresModel->getOffresFilteredPaginated($filters, $this->offresPerPage, $offset);
        } else {
            // Sinon on affiche simplement la liste paginee de toutes les offres.
            $taboffres = $this->offresModel->getAllOffres();
            $totalCount = count($taboffres);
            $paginatedOffres = $this->offresModel->getOffresPaginated($this->offresPerPage, $offset);
        }

        $editOffer = null;
        if ($canManage && isset($_GET['edit'])) {
            $editOffer = $this->offresModel->getOffreById((int) $_GET['edit']);
        }

        $entreprisesOptions = $canManage ? $this->offresModel->getEntreprisesOptions() : [];
        $createMode = $canManage && isset($_GET['create']);
        $mode = isset($_GET['mode']) ? trim((string) $_GET['mode']) : '';

        $manage = $_GET['manage'] ?? '';
        // Messages d'etat affiches apres une creation, modification ou suppression.
        $manageMessage = '';
        if ($manage === 'created') {
            $manageMessage = 'Offre creee avec succes.';
        } elseif ($manage === 'updated') {
            $manageMessage = 'Offre modifiee avec succes.';
        } elseif ($manage === 'deleted') {
            $manageMessage = 'Offre supprimee avec succes.';
        } elseif ($manage === 'invalid') {
            $manageMessage = 'Veuillez renseigner titre, description, ville et entreprise.';
        } elseif ($manage === 'error') {
            $manageMessage = 'Une erreur est survenue pendant la mise a jour.';
        } elseif ($mode === 'edit') {
            $manageMessage = 'Selectionnez une offre puis cliquez sur Modifier.';
        } elseif ($mode === 'delete') {
            $manageMessage = 'Selectionnez une offre puis cliquez sur Supprimer.';
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
                'querySuffix' => $querySuffix,
                'canManage' => $canManage,
                'createMode' => $createMode,
                'entreprisesOptions' => $entreprisesOptions,
            ]);
    }

}