<?php 
require_once 'Controller.php';
require_once __DIR__ . '/../Model/DetailOffre.php';
require_once __DIR__ . '/../Model/offres.php';
require_once __DIR__ . '/../Model/EtudiantActions.php';
use App\Model\offres;
use App\Model\DetailOffre;
use App\Model\EtudiantActions;
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
        $etudiantActions = new EtudiantActions();
        $offresId = $offresModel->getOffresId();
        $id_offre = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id_offre) {
            $offreDetails = $detailOffreModel->getOffreDetails($id_offre);
            if ($offreDetails) {
            $offreDetails['postulants_count'] = $etudiantActions->countOfferApplicants($id_offre);
                $stats = $this->buildOfferStats($offreDetails);
            $userId = $_SESSION['id'] ?? 0;
            $isStudent = isset($_SESSION['role']) && $_SESSION['role'] === 'Etudiant';
            $hasApplied = $isStudent ? $etudiantActions->hasApplied($userId, $id_offre) : false;
            $hasWishlisted = $isStudent ? $etudiantActions->isInWishlist($userId, $id_offre) : false;

                $postulationMessage = '';
                if (isset($_GET['postulation']) && $_GET['postulation'] === 'success') {
                    $postulationMessage = 'Votre candidature a bien ete enregistree.';
                } elseif (isset($_GET['postulation']) && $_GET['postulation'] === 'already') {
                    $postulationMessage = 'Vous avez deja postule a cette offre.';
                }

                $wishlistMessage = '';
                if (isset($_GET['wishlist']) && $_GET['wishlist'] === 'added') {
                    $wishlistMessage = 'Offre ajoutee a votre wishlist.';
                } elseif (isset($_GET['wishlist']) && $_GET['wishlist'] === 'removed') {
                    $wishlistMessage = 'Offre retiree de votre wishlist.';
                } elseif (isset($_GET['wishlist']) && $_GET['wishlist'] === 'already') {
                    $wishlistMessage = 'Cette offre est deja dans votre wishlist.';
                } elseif (isset($_GET['wishlist']) && $_GET['wishlist'] === 'profile_missing') {
                    $wishlistMessage = 'Impossible d\'ajouter cette offre : votre profil etudiant est introuvable.';
                }

                $this->render('detail_offres.twig.html', [
                    'offre' => $offreDetails,
                    'stats' => $stats,
                    'hasApplied' => $hasApplied,
                    'postulationMessage' => $postulationMessage,
                    'hasWishlisted' => $hasWishlisted,
                    'wishlistMessage' => $wishlistMessage
                ]);
            } else {
                echo "Offre non trouvée.";
            }
        } else {
            echo "ID de l'offre manquant.";
        }

    }

    private function buildOfferStats($offreDetails)
    {
        $sections = [
            'description',
            'missions',
            'profil_recherche',
            'avantages',
            'duree',
            'remuneration',
        ];

        $filledSections = 0;
        foreach ($sections as $section) {
            if (!empty(trim((string)($offreDetails[$section] ?? '')))) {
                $filledSections++;
            }
        }

        $completionRate = (int) round(($filledSections / count($sections)) * 100);

        $allText = implode(' ', [
            (string)($offreDetails['description'] ?? ''),
            (string)($offreDetails['missions'] ?? ''),
            (string)($offreDetails['profil_recherche'] ?? ''),
            (string)($offreDetails['avantages'] ?? ''),
        ]);

        $wordCount = str_word_count(strip_tags($allText));
        $readingMinutes = max(1, (int) ceil($wordCount / 180));

        $daysOnline = null;
        if (!empty($offreDetails['date_publication'])) {
            try {
                $publishedDate = new \DateTime($offreDetails['date_publication']);
                $today = new \DateTime();
                $daysOnline = (int) $publishedDate->diff($today)->days;
            } catch (\Exception $e) {
                $daysOnline = null;
            }
        }

        $postulantsCount = $offreDetails['postulants_count'] ?? 0;
        if ($postulantsCount > 0) {
            $completionRate = min(100, $completionRate + 10);
        }

        return [
            'completionRate' => $completionRate,
            'readingMinutes' => $readingMinutes,
            'daysOnline' => $daysOnline,
            'salaryProvided' => !empty(trim((string)($offreDetails['remuneration'] ?? ''))),
            'postulantsCount' => $postulantsCount
        ];
    }

}