<?php
use App\Model\UserConnexion;
use App\Model\ConnexionDB;
use App\Model\offres;
use App\Model\EtudiantActions;

require_once 'Controller.php'; 
require_once __DIR__ . '/../Model/UserConnexion.php';
require_once __DIR__ . '/../Model/offres.php';
require_once __DIR__ . '/../Model/EtudiantActions.php';


class controllerConnexion extends Controller {
    private $user;

    private function deleteStoredStudentFile($publicPath)
    {
        $publicPath = (string) $publicPath;
        if ($publicPath === '' || strpos($publicPath, '/static/') !== 0) {
            return;
        }

        $relativePath = substr($publicPath, strlen('/static/'));
        $targetPath = __DIR__ . '/../../public/static/' . $relativePath;
        $realTarget = realpath($targetPath);
        $realStatic = realpath(__DIR__ . '/../../public/static');

        if ($realTarget === false || $realStatic === false) {
            return;
        }

        if (strpos($realTarget, $realStatic) === 0 && is_file($realTarget)) {
            @unlink($realTarget);
        }
    }

    private function moveStudentFile($userId, $file, array $allowedExtensions, $prefix)
    {
        if (!isset($file) || !isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return [null, null];
        }

        $extension = strtolower((string) pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($extension === '' || !in_array($extension, $allowedExtensions, true)) {
            return [null, 'Format de fichier non autorise.'];
        }

        $staticRoot = realpath(__DIR__ . '/../../public/static');
        if ($staticRoot === false) {
            return [null, 'Le dossier public/static est introuvable.'];
        }

        $uploadDir = $staticRoot . '/etudiants';
        $publicPrefix = '/static/etudiants';

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            $uploadDir = $staticRoot;
            $publicPrefix = '/static';
        }

        if (!is_writable($uploadDir)) {
            return [null, 'Le dossier de stockage n est pas accessible en ecriture.'];
        }

        $safeName = $prefix . '_' . (int) $userId . '_' . time() . '_' . uniqid('', true) . '.' . $extension;
        $targetPath = $uploadDir . '/' . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return [null, 'Echec pendant l enregistrement du fichier.'];
        }

        return [$publicPrefix . '/' . $safeName, null];
    }
    
    public function __construct($url) {
        parent::__construct(); 
        $db = new ConnexionDB();
        $conn = $db->getConnection();
        $this->user = new UserConnexion($conn);
    }

    public function index() {
        $this->render('connexion.twig.html', [
            'message' => 'Bienvenue !'
        ]);
    }

    public function indexetudiant(&$email) {
        $offresModel = new offres();
        $etudiantActions = new EtudiantActions();
        $userId = $_SESSION['id'] ?? 0;
        $etudiantProfile = $etudiantActions->getEtudiantProfile($userId);

        $profileMessage = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profileAction = $_POST['profile_action'] ?? 'upload';

            if ($profileAction === 'remove_photo') {
                if (!empty($etudiantProfile['photo'])) {
                    $this->deleteStoredStudentFile($etudiantProfile['photo']);
                }
                if ($etudiantActions->clearEtudiantFile($userId, 'photo')) {
                    $profileMessage = 'Photo retiree avec succes.';
                } else {
                    $profileMessage = 'Impossible de retirer la photo.';
                }
                $etudiantProfile = $etudiantActions->getEtudiantProfile($userId);
            } elseif ($profileAction === 'remove_cv') {
                if (!empty($etudiantProfile['cv'])) {
                    $this->deleteStoredStudentFile($etudiantProfile['cv']);
                }
                if ($etudiantActions->clearEtudiantFile($userId, 'cv')) {
                    $profileMessage = 'CV retire avec succes.';
                } else {
                    $profileMessage = 'Impossible de retirer le CV.';
                }
                $etudiantProfile = $etudiantActions->getEtudiantProfile($userId);
            } else {
            $photoPath = null;
            $cvPath = null;

            [$newPhotoPath, $photoError] = $this->moveStudentFile(
                $userId,
                $_FILES['photo'] ?? null,
                ['jpg', 'jpeg', 'png', 'webp', 'gif'],
                'photo'
            );
            if ($photoError !== null) {
                $profileMessage = $photoError;
            } elseif ($newPhotoPath !== null) {
                $photoPath = $newPhotoPath;
            }

            [$newCvPath, $cvError] = $this->moveStudentFile(
                $userId,
                $_FILES['cv'] ?? null,
                ['pdf', 'doc', 'docx'],
                'cv'
            );
            if ($cvError !== null && $profileMessage === '') {
                $profileMessage = $cvError;
            } elseif ($newCvPath !== null) {
                $cvPath = $newCvPath;
            }

            if ($profileMessage === '' && ($photoPath !== null || $cvPath !== null)) {
                if ($etudiantActions->updateEtudiantFiles($userId, $photoPath, $cvPath)) {
                    $profileMessage = 'Profil mis a jour avec succes.';
                } else {
                    $profileMessage = 'Impossible de sauvegarder les informations du profil.';
                }
            } elseif ($profileMessage === '') {
                $profileMessage = 'Aucun nouveau fichier a enregistrer.';
            }
            $etudiantProfile = $etudiantActions->getEtudiantProfile($userId);
            }
        }

        $applicationsWithStatus = $etudiantActions->getApplicationsWithStatus($userId);

        $appliedOfferIds = $etudiantActions->getAppliedOfferIds($userId);
        $wishlistOfferIds = $etudiantActions->getWishlistOfferIds($userId);

        $appliedOffers = $offresModel->getOffresByIds($appliedOfferIds);
        $wishlistOffers = $offresModel->getOffresByIds($wishlistOfferIds);

        $selectedView = $_GET['view'] ?? 'all';
        if (!in_array($selectedView, ['all', 'candidatures', 'wishlist'], true)) {
            $selectedView = 'all';
        }

        $this->render('connexion_etudiant.twig.html', [
            'message' => 'Bienvenue !',
            'Utilisateur' => $this->user->getuserprenom($email),
            'profileMessage' => $profileMessage,
            'profilePhoto' => $etudiantProfile['photo'] ?? null,
            'profileCv' => $etudiantProfile['cv'] ?? null,
            'applicationsWithStatus' => $applicationsWithStatus,
            'appliedOffers' => $appliedOffers,
            'wishlistOffers' => $wishlistOffers,
            'selectedView' => $selectedView,
        ]);
    }

    public function indexadmin(&$email) {
        $this->render('connexion_admin.twig.html', [
            'message' => 'Bienvenue Admin !'
        ]);
    }

    public function indexpilote(&$email) {
        $this->render('connexion_pilote.twig.html', [
           'Utilisateur' => $this->user->getuserprenom($email),
        ]);
    }

        public function login($email, $password) {
        $result = $this->user->login($email, $password);
        return $result;
        }

        public function getuserrole($email) {
        $role = $this->user->getuserrole($email);
        return $role;
        }

        public function getId($email) {
        $id = $this->user->getIdByEmail($email);
        return $id; 
        }

}