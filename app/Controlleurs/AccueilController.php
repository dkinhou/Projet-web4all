<?php
//Contrôleur pour la page d'accueil 
class AccueilController{
	public function index(){
		//On charge index.html
		readfile(__DIR__ . '/../../index.html');
	}
}
