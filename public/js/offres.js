//Script JavaScript pour pouvoir ouvrir et fermer la popup des filtres 

//Tout d'abord on va récupérer les éléments par leur id 
const btnFiltres = document.getElementById('btn-filtres');
const popupOverlay = document.getElementById('popup-overlay');
const btnFermer = document.getElementById('btn-fermer');

//Ensuite on ajoute la classe "active quand on clique sur "Tous les filtres" 
//Cette classe va rendre la fenêtre visible en CSS
btnFiltres.addEventListener('click', function(){
    popupOverlay.classList.add('active');
});

//Puis on va retirer la classe "active" lorsque l'on clique sur "x" et la fenêtre redevient cachée
btnFermer.addEventListener('click', function(event){
        popupOverlay.classList.remove('active');
});

//On va aussi fermer la fenêtre si on clique en dehors de celle-ci 
popupOverlay.addEventListener('click', function(event){
    if (event.target === popupOverlay){
        popupOverlay.classList.remove('active');
    }
});
