<?php
//On va faire un tableau qui va simuler les offres de stage et qui sera remplacée par la BDD
$offres = [
    [
        "titre" => "Développeur Web Fullstack",
        "entreprise" => "WebAdia",
        "ville" => "Lyon",
        "duree"=> "6 mois",
        "remuneration" => "800 - 1000€",
        "contrat" => "Stage",
        "competences" => ["PHP", "HTML/CSS"],
        "candidatures" => 12,
        "date" => "02/03/2026"
    ],
    [
        "titre" => "Développeur Web Frontend", 
        "entreprise" => "WebAdia",
        "ville" => "Paris",
        "duree" => "6 mois",
        "remuneration" => "800 - 1000€", 
        "contrat" => "Stage",
        "competences" => ["PHP", "HTML/CSS"],
        "candidatures" => 5,
        "date" => "04/03/2026"
    ],
];

//Paramètres de la pagination 
$offresParPage = 2;
//On Récupére le numéro de la page dans l'URL (si jamais on l'a pas c'est que l'on est sur la page 1)
$pageActuelle = isset($_GET['page'])?(int)$_GET['page'] : 1;
//On calcule le nombre total de pages 
$totalOffres = count($offres);
$totalPages = ceil($totalOffres / $offres_par_page);
//On calcule quelles offres afficher sur cette page 
$debut = ($pageActuelle - 1) * $offresParPage;
$offresAffichees = array_slice($offres, $debut, $offresParPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageAlternance - trouver votre opportunité</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="offres.css">
</head>
    <!--Le header et et la navbar sont les mêmes que pour offres.html -->
<body>
    <header>
        <nav class="navbar">
            <div class="logo"> 
                <div class="logo-image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-suitcase-lg" viewBox="0 0 16 16">
                        <path d="M5 2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2h3.5A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5H14a.5.5 0 0 1-1 0H3a.5.5 0 0 1-1 0h-.5A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2zm1 0h4a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1M1.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5H3V3zM15 12.5v-9a.5.5 0 0 0-.5-.5H13v10h1.5a.5.5 0 0 0 .5-.5m-3 .5V3H4v10z"/>
                    </svg>
                </div>
                <div class="logo-text">StageAlternance</div>
            </div>
            <div class="nav-links">
                <div class="nav-icone">
                    <div class="icone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="30" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"/>
                        </svg>
                    </div>
                    <a href="index.html">Accueil</a>
                </div>
                <div class="nav-icone">
                    <div class="icone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="30" fill="currentColor" class="bi bi-suitcase-lg" viewBox="0 0 16 16">
                            <path d="M5 2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2h3.5A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5H14a.5.5 0 0 1-1 0H3a.5.5 0 0 1-1 0h-.5A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2zm1 0h4a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1M1.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5H3V3zM15 12.5v-9a.5.5 0 0 0-.5-.5H13v10h1.5a.5.5 0 0 0 .5-.5m-3 .5V3H4v10z"/>
                        </svg>
                    </div>
                    <a href="offres.html">Offres</a>
                </div>
                <div class="nav-icone">
                    <div class="icone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16">
                        <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/>
                        <path d="M2 11h1v1H2zm2 0h1v1H4zm-2 2h1v1H2zm2 0h1v1H4zm4-4h1v1H8zm2 0h1v1h-1zm-2 2h1v1H8zm2 0h1v1h-1zm2-2h1v1h-1zm0 2h1v1h-1zM8 7h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zM8 5h1v1H8zm2 0h1v1h-1zm2 0h1v1h-1zm0-2h1v1h-1z"/>
                        </svg>
                    </div>
                    <div><a href="entreprises.html">Entreprises</a></div>
                </div>
                <div><a href="candidat.html" class="nav-btn"><span>Candidat</span></a></div>
            </div>
        </nav>
    </header>

    <main>
        <div class ="barre-recherche">
            <span class="champ-recherche"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
                <input type="text" placeholder="Intitulé du poste...">
            </span>
            <span class="champ-recherche">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-suitcase-lg" viewBox="0 0 16 16">
                <path d="M5 2a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2h3.5A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5H14a.5.5 0 0 1-1 0H3a.5.5 0 0 1-1 0h-.5A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2zm1 0h4a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1M1.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5H3V3zM15 12.5v-9a.5.5 0 0 0-.5-.5H13v10h1.5a.5.5 0 0 0 .5-.5m-3 .5V3H4v10z"/>
            </svg>
            <select>
                <option value="">Type de contrat</option>
                <option value="stage">Stage</option>
                <option value="alternance">Alternance</option>
            </select>
        </span>
        <span class="champ-recherche">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg>
            <input type="text" placeholder="Ville...">
        </span>
            <button class="btn-rechercher">Rechercher</button>
        </div>

                <!-- Bouton et popup des filtres -->
        <div class="filtres-bar">
            <!-- Bouton qui déclenchera l'ouverture la fenêtre popup -->
             <!-- L'id va permettre à JavaScipt de retrouver le bouton -->
            <button class="btn-filtres" id="btn-filtres">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3M2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1z"/>
                </svg>
                Tous les filtres
            </button>
        </div>

        <section class="liste-offres">
            <div class="liste-header">
                <h2>Offres de stage disponibles</h2>
                <div class="liste-controls">
                    <!-- On va incorporer le PHP pour le nombre total d'offres -->
                    <span class="compteur"><?php echo $totalOffres; ?> offres trouvées</span>
                    <select class="tri-offres" id="tri-offres">
                        <option value="recent">Plus récentes</option>
                        <option value="ancien">Pertinence</option>
                        <option value="remuneration">Rémunération</option>
                    </select>
                </div>
            </div>

            <!-- On va faire une boucle qui va générer les cartes automatiquement -->
            <?php foreach($offresAffichees as $offre): ?>
            <article class = "carte-offre">
                <div class="carte-header">
                    <h3><?php echo $offre['titre']; ?></h3>
                    <span class="tag-contrat"><?php echo $offre['contrat']; ?></span>
                </div>
                <p class="nom-entreprise"><?php echo $offre['entreprise']; ?></p>
                <div class="carte-infos">
                    <span class="info-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                            <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                        <?php echo $offre['ville']; ?>
                    </span>
                    <span class="info-item">
                        <span class="info-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0"/>
                            </svg>
                            <?php echo $offre['duree']; ?>
                        </span>
                    <span class="info-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                            <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                            <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z"/>
                        </svg>
                        <?php echo $offre['remuneration']; ?>
                    </span>
                </div>
                <div class="competences">  
                    <?php foreach($offre['competences'] as $competence): ?>
                        <span class="competences"><?php echo $competence; ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="carte-footer">
                    <span class="nb-candidatures">
                        <?php echo $offre['candiatures']; ?> candidatures · Publié le <?php echo $offre['date']; ?>
                    </span>
                    <button class="details-btn">Voir les détails</button> 
                </div>
            </article>
            <?php endforeach; ?>

            <!-- Pour la pagination-->
            <div class="pagination">
                <!-- On va créer un bouton "Précédent" qui sera désactivé si l'on est sur la page 1 -->
                <?php if($pageActuelle > 1):?>
                    <a href="?page=<?php echo $page_actuelle - 1; ?>" class="page-btn"> Précédent</a>
                <?php else: ?>
                
                <!-- Maintenant occupons nous des numéros de page -->
                 <?php for($i=1; $i <= $totalPages; $i++):?>
                    <a href="?page=<?php echo $i; ?>"
                        class="page-btn <?php echo ($i == $pageActuelle) ? 'active' : ''; ?>">
                        


            

            

