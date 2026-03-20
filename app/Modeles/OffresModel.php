<?php
//Ici on va mettre le modèle qui va gérer les données des offres 
class OffresModel{
	//Pour retourner toutes les offres disponibles 
	public function getOffres(){
		return[
		  [
		  	"titre" => "Développeur Web Fullstack",
			"entreprise" => "WebAdia",
			"ville"=> "Lyon",
			"duree"=> "6 mois",
			"remuneration" => "800-1000€",
			"contrat" => "Stage",
			"competences" => ["PHP","HTML/CSS"],
			"candidatures"=> 12,
			"date" => "02/03/2026"

		],
		[
			"titre" => "Développeur Web Frontend",
                "entreprise" => "CréaWeb",
                "ville" => "Paris, France",
                "duree" => "6 mois",
                "remuneration" => "800-1000€",
                "contrat" => "Stage",
                "competences" => ["PHP", "HTML/CSS"],
                "candidatures" => 5,
                "date" => "04/03/2026"
         	],
       	];
      }
}

