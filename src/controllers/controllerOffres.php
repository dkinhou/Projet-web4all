<?php
require_once 'Controller.php';
require_once __DIR__ . '/../Model/Offre.php';
require_once __DIR__ . '/../Model/Entreprise.php';
require_once __DIR__ . '/../Model/Pagination.php';

class controllerOffres extends Controller {

    public function __construct($url) {
        parent::__construct();

        $action = $url[1] ?? '';
        $id     = $url[2] ?? null;

        if ($action === 'creer') {
            $this->creer();
        } elseif ($action === 'modifier') {
            $this->modifier((int)$id);
        } elseif ($action === 'supprimer') {
            $this->supprimer((int)$id);
        } elseif (is_numeric($action)) {
            $this->detail((int)$action);
        } else {
            $this->index();
        }
    }

    private function index(): void {
        $offreModel = new Offre();
        $keyword    = trim($_GET['keyword'] ?? '');

        // On récupère TOUTES les offres d'abord
        // Ensuite la pagination se charge de n'en montrer que 10
        if ($keyword) {
            $toutesLesOffres = $offreModel->search($keyword);
        } else {
            $toutesLesOffres = $offreModel->getAll();
        }

        // On récupère le numéro de page dans l'URL
        // Si pas de page dans l'URL → on est à la page 1 par défaut
        $pageActuelle = (int)($_GET['page'] ?? 1);

        // On crée l'objet Pagination
        // 10 = nombre d'éléments par page comme demandé dans le prosit
        $pagination = new Pagination($toutesLesOffres, 10, $pageActuelle);

        // On envoie à Twig seulement les offres de la page courante
        $this->render('offres.twig.html', [
            'offres'     => $pagination->getElements(),
            'pagination' => $pagination,
            'keyword'    => $keyword
        ]);
    }

    private function detail(int $id): void {
        $offreModel = new Offre();
        $offre = $offreModel->getById($id);

        if (!$offre) {
            $this->render('viewError.twig.html', [
                'message' => 'Offre introuvable.'
            ]);
            return;
        }

        $this->render('offre_detail.twig.html', [
            'offre' => $offre
        ]);
    }

    private function creer(): void {
        if (!$this->checkRole(['admin', 'pilote'])) {
            return;
        }

        $entrepriseModel = new Entreprise();
        $entreprises     = $entrepriseModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $offreModel = new Offre();
            $offreModel->create([
                'titre'          => trim($_POST['titre'] ?? ''),
                'description'    => trim($_POST['description'] ?? ''),
                'remuneration'   => $_POST['remuneration'] ?? 0,
                'date_offre'     => $_POST['date_offre'] ?? '',
                'entreprise_id'  => $_POST['entreprise_id'] ?? 0
            ]);
            header('Location: /offres');
            exit;
        }

        $this->render('offre_form.twig.html', [
            'entreprises' => $entreprises,
            'offre'       => null
        ]);
    }

    private function modifier(int $id): void {
        if (!$this->checkRole(['admin', 'pilote'])) {
            return;
        }

        $offreModel      = new Offre();
        $entrepriseModel = new Entreprise();
        $offre           = $offreModel->getById($id);
        $entreprises     = $entrepriseModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $offreModel->update($id, [
                'titre'          => trim($_POST['titre'] ?? ''),
                'description'    => trim($_POST['description'] ?? ''),
                'remuneration'   => $_POST['remuneration'] ?? 0,
                'date_offre'     => $_POST['date_offre'] ?? '',
                'entreprise_id'  => $_POST['entreprise_id'] ?? 0
            ]);
            header('Location: /offres');
            exit;
        }

        $this->render('offre_form.twig.html', [
            'offre'       => $offre,
            'entreprises' => $entreprises
        ]);
    }

    private function supprimer(int $id): void {
        if (!$this->checkRole(['admin', 'pilote'])) {
            return;
        }

        $offreModel = new Offre();
        $offreModel->delete($id);
        header('Location: /offres');
        exit;
    }
}
