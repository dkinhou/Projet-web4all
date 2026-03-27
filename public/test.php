<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use App\Model\ConnexionDB;
use App\Model\UserConnexion;
require_once __DIR__ . '/../src/Model/ConnexionDB.php';
require_once __DIR__ . '/../src/Model/UserConnexion.php';
$db = new ConnexionDB();
$conn = $db->getConnection();
$userConnexion = new UserConnexion($conn);
$register = $userConnexion->register('deograciaskinhou200@gmail.com', '123456', 'Administrateur', 'Deo', 'KINHOU');
$register = $userConnexion->register('deograciaskinhou9@gmail.com', '123456', 'Pilote', 'Oswald', 'HENRI');
$register = $userConnexion->register('deokinhou9@gmail.com', '123456', 'Etudiant', 'Thomas', 'DUPINT');


if($conn && $register) {
   echo "<script> alert('Connexion réussie à la base de données'); </script>";
                                                  
} else {
   echo "<script> alert('Échec de la connexion à la base de données'); </script>";
                              
}

?>
                        
                        