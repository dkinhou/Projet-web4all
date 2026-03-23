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

//Pour gérer le bouton qui permet d'ajouter à la wishlist 
//Sélectionnons tous les boutons wishlist de la page 
const boutonsWishlist = document.querySelectorAll('.btn-wishlist'); 
//Ensuite on ajoute un écouteur d'événement à chaque bouton
boutonsWishlist.forEach(function(bouton){
    bouton.addEventListener('click', function(){
        //On va basculer la classe "active" pour changer l'apparence
        bouton.classList.toggle('active'); 
        //On va changer le SVG du bouton en fonction de son état 
        if (bouton.classList.contains('active')){
            //Si le coeur est plein 
            bouton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/></svg>';
        }else{
            //Si le coeur est vide
            bouton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/></svg>';
        }
    });
});