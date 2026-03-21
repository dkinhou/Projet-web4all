<?php

require_once __DIR__ . '/../src/Model/connexionDB.php';
$db = new ConnexionDB();
$conn = $db->getConnection();


if($conn) {
   echo "<script> alert('Connexion réussie à la base de données'); </script>";
                                                  
} else {
   echo "<script> alert('Échec de la connexion à la base de données'); </script>";
                              
}

?>
                        