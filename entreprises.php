<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageAlternance - trouver votre opportunité</title>
    <link rel="stylesheet" href="style.css"> 
</head>

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
                    <a href="offres.html">Offres de stage</a>
                </div>
                <div class="nav-icone">
                    <div class="icone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-buildings" viewBox="0 0 16 16">
                            <path d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022M6 8.694 1 10.36V15h5zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5z"/>
                        </svg>
                    </div>
                    <a href="entreprises.html">Entreprises</a>
                </div>
                <a href="candidat.html" class="nav-btn"><span>Candidat</span></a>
            </div>
        </nav>
    </header>
     
                <?php
            /**
             * pagination.php — Exercice 2 : Pagination dynamique
             *
             * Déploiement Apache :
             *   - Copier ce fichier dans /var/www/html/ (Linux/LAMP)
             *     ou dans C:\xampp\htdocs\ (Windows/XAMPP)
             *   - Accéder via http://localhost/pagination.php
             *   - Pour changer de page : http://localhost/pagination.php?page=2
             */


            // ─────────────────────────────────────────────
            // 1. DONNÉES — Tableau des 50 entreprises
            // ─────────────────────────────────────────────

            /*
            * Dans un cas réel, ces données viendraient d'une base de données (PDO/MySQL).
            * Ici on les stocke en dur dans un tableau PHP associatif multidimensionnel.
            * Chaque entrée est elle-même un tableau avec les clés 'nom', 'secteur', 'ville'.
            */
            $entreprises = [
                ['nom' => 'TechCorp',       'secteur' => 'Technologie',    'ville' => 'Paris'],
                ['nom' => 'FinSoft',        'secteur' => 'Finance',         'ville' => 'Londres'],
                ['nom' => 'AutoDrive',      'secteur' => 'Automobile',      'ville' => 'Berlin'],
                ['nom' => 'BioLife',        'secteur' => 'Santé',           'ville' => 'Lyon'],
                ['nom' => 'GreenEnergy',    'secteur' => 'Énergie',         'ville' => 'Bordeaux'],
                ['nom' => 'DataStream',     'secteur' => 'Big Data',        'ville' => 'Paris'],
                ['nom' => 'RetailPro',      'secteur' => 'Commerce',        'ville' => 'Marseille'],
                ['nom' => 'CloudNine',      'secteur' => 'Cloud',           'ville' => 'Toulouse'],
                ['nom' => 'MediCare',       'secteur' => 'Médical',         'ville' => 'Nantes'],
                ['nom' => 'EduTech',        'secteur' => 'Éducation',       'ville' => 'Lille'],
                ['nom' => 'CyberShield',    'secteur' => 'Cybersécurité',   'ville' => 'Rennes'],
                ['nom' => 'LogiFlow',       'secteur' => 'Logistique',      'ville' => 'Strasbourg'],
                ['nom' => 'AgriSmart',      'secteur' => 'Agriculture',     'ville' => 'Dijon'],
                ['nom' => 'TravelEase',     'secteur' => 'Tourisme',        'ville' => 'Nice'],
                ['nom' => 'MediaPulse',     'secteur' => 'Médias',          'ville' => 'Paris'],
                ['nom' => 'ArchDesign',     'secteur' => 'Architecture',    'ville' => 'Montpellier'],
                ['nom' => 'FoodTech',       'secteur' => 'Alimentation',    'ville' => 'Grenoble'],
                ['nom' => 'LegalAI',        'secteur' => 'Juridique',       'ville' => 'Paris'],
                ['nom' => 'TransportGo',    'secteur' => 'Transport',       'ville' => 'Rouen'],
                ['nom' => 'SmartHome',      'secteur' => 'Domotique',       'ville' => 'Caen'],
                ['nom' => 'RoboTech',       'secteur' => 'Robotique',       'ville' => 'Grenoble'],
                ['nom' => 'PrintFab',       'secteur' => 'Impression 3D',   'ville' => 'Clermont-Fd'],
                ['nom' => 'SkyDrones',      'secteur' => 'Aérien',          'ville' => 'Toulouse'],
                ['nom' => 'HealthAI',       'secteur' => 'IA Santé',        'ville' => 'Paris'],
                ['nom' => 'PaySecure',      'secteur' => 'Paiement',        'ville' => 'Nantes'],
                ['nom' => 'InsurBot',       'secteur' => 'Assurance',       'ville' => 'Lyon'],
                ['nom' => 'WaterTech',      'secteur' => 'Eau',             'ville' => 'Montpellier'],
                ['nom' => 'SolarPeak',      'secteur' => 'Énergie solaire', 'ville' => 'Perpignan'],
                ['nom' => 'CinemAI',        'secteur' => 'Cinéma',          'ville' => 'Paris'],
                ['nom' => 'PharmaLink',     'secteur' => 'Pharmacie',       'ville' => 'Strasbourg'],
                ['nom' => 'NetBridge',      'secteur' => 'Télécoms',        'ville' => 'Paris'],
                ['nom' => 'DevOpsHero',     'secteur' => 'DevOps',          'ville' => 'Bordeaux'],
                ['nom' => 'UrbanMove',      'secteur' => 'Mobilité',        'ville' => 'Rennes'],
                ['nom' => 'BlockVault',     'secteur' => 'Blockchain',      'ville' => 'Paris'],
                ['nom' => 'AIFactory',      'secteur' => 'Intelligence IA', 'ville' => 'Sophia Antipolis'],
                ['nom' => 'GreenBuild',     'secteur' => 'Construction',    'ville' => 'Tours'],
                ['nom' => 'SportTech',      'secteur' => 'Sport',           'ville' => 'Paris'],
                ['nom' => 'AudioWave',      'secteur' => 'Audio',           'ville' => 'Nantes'],
                ['nom' => 'MarketDash',     'secteur' => 'Marketing',       'ville' => 'Lille'],
                ['nom' => 'CodeAcademy',    'secteur' => 'Formation',       'ville' => 'Paris'],
                ['nom' => 'VisionLab',      'secteur' => 'Vision par ordi', 'ville' => 'Grenoble'],
                ['nom' => 'EventPro',       'secteur' => 'Événementiel',    'ville' => 'Cannes'],
                ['nom' => 'NanoMat',        'secteur' => 'Matériaux',       'ville' => 'Bordeaux'],
                ['nom' => 'CryptoNet',      'secteur' => 'Cryptographie',   'ville' => 'Paris'],
                ['nom' => 'HotelSmart',     'secteur' => 'Hôtellerie',      'ville' => 'Monaco'],
                ['nom' => 'SpaceLink',      'secteur' => 'Aérospatial',     'ville' => 'Toulouse'],
                ['nom' => 'MusicStream',    'secteur' => 'Streaming',       'ville' => 'Paris'],
                ['nom' => 'RecycloBot',     'secteur' => 'Recyclage',       'ville' => 'Strasbourg'],
                ['nom' => 'QuantumSoft',    'secteur' => 'Quantique',       'ville' => 'Saclay'],
                ['nom' => 'FintechPay',     'secteur' => 'Fintech',         'ville' => 'Paris'],
            ];


            // ─────────────────────────────────────────────
            // 2. PARAMÈTRES DE PAGINATION
            // ─────────────────────────────────────────────

            /**
             * Nombre d'entreprises affichées par page.
             * Le workshop demande 10 par page.
             */
            const ITEMS_PER_PAGE = 10;

            /*
            * count() retourne le nombre total d'éléments dans le tableau.
            * ceil() arrondit au supérieur pour avoir la bonne dernière page
            * ex : 50 entreprises / 10 par page = 5 pages (exact)
            * ex : 53 entreprises / 10 par page = ceil(5.3) = 6 pages
            */
            $totalItems = count($entreprises);
            $totalPages = (int) ceil($totalItems / ITEMS_PER_PAGE);


            // ─────────────────────────────────────────────
            // 3. RÉCUPÉRATION ET SÉCURISATION DE LA PAGE
            // ─────────────────────────────────────────────

            /*
            * $_GET['page'] contient la valeur du paramètre "page" dans l'URL.
            * ex : http://localhost/pagination.php?page=3 → $_GET['page'] = "3"
            *
            * Sécurisation :
            *   - isset() : vérifie que le paramètre existe
            *   - is_numeric() : vérifie que c'est un nombre (pas "abc" ou "<script>")
            *   - (int) : cast en entier (supprime les décimales et les espaces)
            *   - max(1, ...) : garantit un minimum de 1 (pas de page 0 ou négative)
            *   - min($totalPages, ...) : garantit qu'on ne dépasse pas la dernière page
            */
            $page = isset($_GET['page']) && is_numeric($_GET['page'])
                ? (int) $_GET['page']
                : 1;

            // Bornes de sécurité
            $page = max(1, min($page, $totalPages));


            // ─────────────────────────────────────────────
            // 4. CALCUL DE L'OFFSET ET DÉCOUPAGE DU TABLEAU
            // ─────────────────────────────────────────────

            /*
            * L'offset est l'index de départ dans le tableau global.
            * Page 1 → offset 0  (éléments 0 à 9)
            * Page 2 → offset 10 (éléments 10 à 19)
            * Page 3 → offset 20 (éléments 20 à 29)
            * etc.
            */
            $offset = ($page - 1) * ITEMS_PER_PAGE;

            /*
            * array_slice($tableau, $debut, $longueur) extrait une portion du tableau.
            * $offset = index de début
            * ITEMS_PER_PAGE = nombre d'éléments à extraire
            * Le tableau original $entreprises n'est PAS modifié.
            */
            $currentItems = array_slice($entreprises, $offset, ITEMS_PER_PAGE);

            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Liste des Entreprises – Page <?= $page ?></title>
                <link rel="stylesheet" href="pagination.css">
            </head>
            <body>

            <h1>🏢 Liste des Entreprises Partenaires</h1>
            <p class="subtitle">
                Exercice 2 – Workshop PHP | Pagination dynamique<br>
                <?= $totalItems ?> entreprises au total –
                Page <strong><?= $page ?></strong> / <?= $totalPages ?>
            </p>

            <!-- ─────────────────────────────────────────────
                5. AFFICHAGE DU TABLEAU
                ─────────────────────────────────────────────
                htmlspecialchars() est appliqué sur chaque donnée affichée
                pour prévenir les attaques XSS.
                ENT_QUOTES convertit aussi les guillemets simples et doubles.
                'UTF-8' garantit le bon traitement des caractères accentués.
            -->
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom de l'entreprise</th>
                        <th>Secteur d'activité</th>
                        <th>Ville</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($currentItems as $index => $entreprise): ?>
                    <tr>
                        <!-- Numéro de ligne globale (ex: page 2 commence à 11) -->
                        <td class="row-num"><?= $offset + $index + 1 ?></td>

                        <!-- htmlspecialchars protège contre XSS -->
                        <td><?= htmlspecialchars($entreprise['nom'],     ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($entreprise['secteur'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($entreprise['ville'],   ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


            <!-- ─────────────────────────────────────────────
                6. NAVIGATION ENTRE LES PAGES
                ─────────────────────────────────────────────
                Chaque lien transmet le numéro de page via le paramètre GET "page".
                ex: ?page=2, ?page=3 …
                On désactive "Précédent" à la page 1 et "Suivant" à la dernière page.
            -->
            <nav class="nav" aria-label="Pagination">

                <!-- Première page -->
                <a href="?page=1" <?= $page <= 1 ? 'class="disabled"' : '' ?>>«</a>

                <!-- Précédent -->
                <a href="?page=<?= $page - 1 ?>" <?= $page <= 1 ? 'class="disabled"' : '' ?>>
                    ← Précédent
                </a>

                <?php
                /*
                * Afficher les numéros de page.
                * La page courante est mise en évidence (classe "current").
                * On affiche max 5 pages autour de la page courante pour ne pas surcharger.
                */
                $range = 2; // Pages avant et après la page courante à afficher
                for ($i = 1; $i <= $totalPages; $i++):
                    // Afficher seulement les pages proches de la courante
                    if ($i === 1 || $i === $totalPages || abs($i - $page) <= $range):
                ?>
                    <?php if ($i === $page): ?>
                        <span class="current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php
                    elseif (abs($i - $page) === $range + 1):
                        echo '<span>…</span>'; // Ellipsis
                    endif;
                endfor;
                ?>

                <!-- Suivant -->
                <a href="?page=<?= $page + 1 ?>" <?= $page >= $totalPages ? 'class="disabled"' : '' ?>>
                    Suivant →
                </a>

                <!-- Dernière page -->
                <a href="?page=<?= $totalPages ?>" <?= $page >= $totalPages ? 'class="disabled"' : '' ?>>»</a>

            </nav>

            <p class="pag-info">
                Affichage des entreprises <?= $offset + 1 ?> à <?= min($offset + ITEMS_PER_PAGE, $totalItems) ?>
                sur <?= $totalItems ?> au total
            </p>

            </body>
            </html>

    
   

    <footer>
        <div class="footer-content">
            <div class="footer-brand">
                <h2>StageAlternance</h2>
                <p>Plateforme de recherche de stage pour tous les étudiants</p>
            </div>
            <div class="footer-group">
                <h2>Liens rapides</h2>
                <div class="footer-links"><a href="offres.html">Offres de stage</a></div>
                <div class="footer-links"><a href="entreprises.html">Entreprises</a></div>
                <div class="footer-links"><a href="#">Mentions Légales</a></div>
            </div>
            <div class="footer-group">
                <h2>Contact</h2>
                <div class="footer-info"><a href="mailto:contact@stagealternance.fr">Mail: contact@stagealternance.fr</a></div>
                <div class="footer-info"><a href="tel:0123456789">Tel: 01 23 45 67 89</a></div>
                <div class="footer-info"><a href="#" target="_blank">Adresse: Campus CESI France</a></div>
            </div>
        </div>
        <div class="barre"></div>
        <div class="copyright">© 2026 StageAlternance. Tous droits réservés.</div>
    </footer>
</body>
</html>