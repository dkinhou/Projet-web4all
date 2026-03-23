<?php

require_once __DIR__ . '/../src/Model/connexionDB.php';
require_once __DIR__ . '/../src/Model/userconnexion.php';
$db = new ConnexionDB();
$conn = $db->getConnection();
$userConnexion = new UserConnexion($conn);
$register = $userConnexion->register('deograciaskinhou200@gmail.com', '123456', 'Administrateur', 'Deo', 'KINHOU');
$register = $userConnexion->register('deograciaskinhou9@gmail.com', '123456', 'Pilote', 'Oswald', 'HENRI');


if($conn && $register) {
   echo "<script> alert('Connexion réussie à la base de données'); </script>";
                                                  
} else {
   echo "<script> alert('Échec de la connexion à la base de données'); </script>";
                              
}

?>
                        
                        