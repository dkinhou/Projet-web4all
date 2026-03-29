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

        $taboffres = $this->offresModel->getAllOffres();
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $this->offresPerPage;
        $paginatedOffres = $this->offresModel->getOffresPaginated($this->offresPerPage, $offset);

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

            $this->render('offres.twig.html', [
                'offres' => $paginatedOffres,
                'compteur' => count($taboffres),
                'currentPage' => $currentPage,
                'totalPages' => ceil(count($taboffres) / $this->offresPerPage),
                'editOffer' => $editOffer,
                'manageMessage' => $manageMessage
            ]);
    }

}