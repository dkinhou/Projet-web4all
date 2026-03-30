-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 30 mars 2026 à 07:47
-- Version du serveur : 8.0.45-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `stagealternance_bdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE `adresses` (
  `id_addresse` int NOT NULL,
  `code_postal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pays` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_entreprise` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

CREATE TABLE `candidatures` (
  `id_candidature` int NOT NULL,
  `date_candidature` date NOT NULL,
  `id_utilisateur` int NOT NULL,
  `id_offre` int NOT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id_candidature`, `date_candidature`, `id_utilisateur`, `id_offre`, `statut`) VALUES
(1, '2026-03-29', 8, 1, 'En attente');

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id_entreprise` int NOT NULL,
  `nom_societe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secteur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_evaluation` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id_entreprise`, `nom_societe`, `secteur`, `description`, `contact`, `id_evaluation`) VALUES
(1, 'TechNova', 'Informatique', 'Solutions Cloud et IA', 'rh@technova.fr', 0),
(2, 'Batibuild', 'Construction', 'Bâtiment basse consommation', 'contact@batibuild.com', 0),
(3, 'BioSante', 'Santé', 'Laboratoire de recherche', 'recrutement@biosante.fr', 0),
(4, 'GreenWheel', 'Automobile', 'Constructeur de vélos électriques', 'hr@greenwheel.fr', 0),
(5, 'Finanza', 'Finance', 'Banque d\'investissement', 'jobs@finanza.com', 0),
(6, 'CyberShield', 'Cybersécurité', 'Protection des données et audits réseau', 'contact@cybershield.fr', 0),
(7, 'EcoLogistics', 'Logistique', 'Transport routier éco-responsable', 'recrutement@ecologistics.com', 0),
(8, 'DataMind', 'IA / Big Data', 'Analyse prédictive pour le marketing', 'jobs@datamind.io', 0),
(9, 'UrbanArch', 'Architecture', 'Urbanisme et conception bioclimatique', 'hello@urbanarch.fr', 0),
(10, 'HealthTech', 'Médical', 'Dispositifs médicaux connectés', 'hr@healthtech.com', 0),
(11, 'VoltDrive', 'Énergie', 'Bornes de recharge pour véhicules électriques', 'contact@voltdrive.fr', 0),
(12, 'Solaris', 'Énergies Renouvelables', 'Installation de panneaux photovoltaïques', 'info@solaris.eu', 0),
(13, 'FoodInov', 'Agroalimentaire', 'Nouveaux produits à base de protéines végétales', 'rh@foodinov.fr', 0),
(14, 'SkyDrone', 'Aéronautique', 'Surveillance par drones civils', 'pilot@skydrone.fr', 0),
(15, 'BlueOcean', 'Écologie Marine', 'Dépollution plastique des océans', 'save@blueocean.org', 0),
(16, 'ConstructO', 'BTP', 'Rénovation de monuments historiques', 'projets@constructo.fr', 0),
(17, 'SoftBank', 'Finance / Fintech', 'Solutions de paiement mobile sécurisé', 'recrutement@softbank.fr', 0),
(18, 'GreenStyle', 'Mode Durable', 'Vêtements en matières recyclées', 'contact@greenstyle.com', 0),
(19, 'SpaceNext', 'Aérospatial', 'Composants pour nanosatellites', 'hr@spacenext.io', 0),
(20, 'EduPlus', 'Éducation', 'Plateforme d apprentissage en ligne personnalisée', 'team@eduplus.fr', 0),
(21, 'HomeSmart', 'Domotique', 'Objets connectés pour la maison', 'support@homesmart.fr', 0),
(22, 'BioGrow', 'Agriculture', 'Solutions de culture hydroponique urbaine', 'contact@biogrow.farm', 0),
(23, 'OptiFlow', 'Conseil / Audit', 'Optimisation des processus industriels', 'audit@optiflow.fr', 0),
(24, 'MediaWave', 'Communication', 'Agence de marketing digital et réseaux sociaux', 'hello@mediawave.fr', 0),
(25, 'LuxuryCloud', 'Luxe', 'Expériences client virtuelles pour marques de luxe', 'rh@luxurycloud.com', 0);

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id_etudiant` int NOT NULL,
  `cv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_pilote` int NOT NULL,
  `id_whishlist` int NOT NULL,
  `id_utilisateur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id_etudiant`, `cv`, `photo`, `id_pilote`, `id_whishlist`, `id_utilisateur`) VALUES
(8, NULL, NULL, 5, 0, 8);

-- --------------------------------------------------------

--
-- Structure de la table `evaluation`
--

CREATE TABLE `evaluation` (
  `id_evaluation` int NOT NULL,
  `note` int NOT NULL,
  `commentaire` text COLLATE utf8mb4_unicode_ci,
  `date_evaluation` date DEFAULT (curdate()),
  `id_utilisateur` int NOT NULL,
  `id_entreprise` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

CREATE TABLE `offres` (
  `id_offre` int NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_publication` date NOT NULL,
  `id_entreprise` int NOT NULL,
  `type_contrat` enum('Stage','Alternance') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duree` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `missions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `profil_recherche` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `remuneration` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avantages` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `id_utilisateur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`id_offre`, `titre`, `description`, `ville`, `date_publication`, `id_entreprise`, `type_contrat`, `duree`, `missions`, `profil_recherche`, `remuneration`, `avantages`, `id_utilisateur`) VALUES
(1, 'Stage Développeur PHP', 'Maintenance d\'un site e-commerce', 'Lyon', '2026-03-15', 1, 'Stage', '6 mois', 'Maintenance corrective et évolutive du site sous Laravel. Optimisation des requêtes SQL et développement de nouveaux modules de paiement API.', 'Étudiant Bac+3/5 en informatique. Maîtrise de PHP 8, MySQL et bases en JavaScript (Vue.js). Autonomie et rigueur technique.', '623.70€ / mois (Gratification minimale)', 'Tickets Restaurant (9€), Prise en charge transport 50%, Télétravail 2j/semaine', 0),
(2, 'Stage Chef de chantier', 'Gestion de projets résidentiels', 'Paris', '2026-03-18', 2, 'Stage', '4 mois', 'Assister le conducteur de travaux dans le suivi quotidien, gestion des approvisionnements matériaux et contrôle de la conformité sécuritaire.', 'Formation Génie Civil. Capacité à lire des plans techniques, sens du commandement et aisance relationnelle sur le terrain.', '700€ / mois', 'Panier repas chantier, Véhicule de service, Prime d outillage', 0),
(3, 'Alternant Analyste Data', 'Analyse des données cliniques', 'Bordeaux', '2026-03-20', 3, 'Alternance', '12 mois', 'Nettoyage de bases de données cliniques, création de dashboards de suivi sous Power BI et automatisation de rapports via scripts Python.', 'Master Data Science ou Statistique. Maîtrise de SQL, Python (Pandas/NumPy). Esprit d analyse et curiosité pour le secteur médical.', 'Selon barème alternance (850€ - 1200€)', 'Accès salle de sport, Ordinateur portable fourni, Prime de fin d année', 0),
(4, 'Alternance Ingénieur Cloud', 'Migration serveurs Azure', 'Lille', '2026-03-21', 1, 'Alternance', '24 mois', 'Migration d infrastructures on-premise vers Azure. Mise en place de pipelines CI/CD et gestion de la conteneurisation via Docker et Kubernetes.', 'Bac+4/5 spécialisé Réseaux/Cloud. Connaissance de Terraform ou Ansible. Anglais technique indispensable.', 'Selon barème alternance (1100€ - 1500€)', 'Forfait mobile pro, Certifications Azure offertes, Fruits et café à volonté', 0),
(5, 'Stage Commercial', 'Développement parc clients', 'Nantes', '2026-03-22', 4, 'Stage', '3 mois', 'Identification de prospects, qualification de fichiers clients et participation aux rendez-vous de négociation avec les ingénieurs d affaires.', 'École de commerce (Bac+2/3). Tempérament de chasseur, excellente élocution et maîtrise des outils CRM (Hubspot/Salesforce).', '623.70€ / mois', 'Primes sur objectifs, Chèques cadeaux, Remboursement transport', 0),
(6, 'Stage Comptable', 'Gestion des comptes annuels', 'Lyon', '2026-03-23', 5, 'Stage', '5 mois', 'Saisie comptable et aide au bilan annuel.', 'BTS/DCG Comptabilité', '650€ / mois', 'Tickets Restaurant, Mutuelle entreprise, 13ème mois au prorata', 0),
(7, 'Stage Technicien SAV', 'Réparation moteurs électriques', 'Marseille', '2026-03-23', 4, 'Stage', '2 mois', 'Réparation et maintenance de moteurs électriques.', 'Bac Pro Électrotechnique', 'Gratification minimale légale', 'Équipements de protection fournis, Indemnités de repas, Prime de risque', 0),
(8, 'Stage Maçon', 'Travaux de gros œuvre', 'Paris', '2026-03-24', 2, 'Stage', '4 mois', 'Maçonnerie traditionnelle et lecture de plans.', 'CAP/BP Maçonnerie', '750€ / mois', 'Prime de zone de chantier, Logement possible en déplacement, Transport', 0),
(9, 'Alternant Administrateur BD', 'Optimisation requêtes SQL', 'Lyon', '2026-03-25', 1, 'Alternance', '12 mois', 'Optimisation de requêtes SQL et gestion de base de données.', 'Bac+3 Informatique', 'Selon barème alternance', 'Accès plateforme de formation en ligne, Horaires flexibles', 0),
(10, 'Alternant Chargé de recrutement', 'Sourcing profils techniques', 'Bordeaux', '2026-03-25', 3, 'Alternance', '12 mois', 'Rédaction d annonces attractives, sourcing sur LinkedIn et jobboards, conduite des entretiens de pré-qualification téléphonique.', 'Master RH ou Psychologie du travail. Excellente capacité de synthèse, aisance au téléphone et intérêt pour les métiers du numérique.', 'Selon barème alternance', 'Remboursement Navigo 100%, Télétravail ponctuel, Afterworks', 0),
(11, 'Développeur Fullstack', 'Développement de nouvelles fonctionnalités sur une application SaaS.', 'LYON', '2026-03-20', 1, 'Alternance', '24 mois', 'Développement Fullstack React/Node.js.', 'Bac+3/5 Développeur', 'Selon barème alternance (Niveau Master)', 'Matériel Apple au choix, Budget formation, Snacks gratuits', 0),
(12, 'Assistant Marketing', 'Aide à la création de campagnes publicitaires sur les réseaux sociaux.', 'PARIS', '2026-03-21', 3, 'Stage', '6 mois', 'Création de campagnes publicitaires digitales.', 'Master Marketing Digital', '800€ / mois', 'Abonnement presse digitale, Tickets Restaurant, Prise en charge transport', 0),
(13, 'Ingénieur Système', 'Maintenance et évolution de l infrastructure serveur Linux.', 'MARSEILLE', '2026-03-22', 4, 'Alternance', '12 mois', 'Maintenance infrastructure serveurs Linux.', 'BTS SIO / Bac+3 Réseaux', 'Selon barème alternance', 'Accès serveurs de test, Lab R&D, Repas d équipe mensuel', 0),
(14, 'Consultant SEO', 'Optimisation du référencement naturel pour divers clients.', 'BORDEAUX', '2026-03-22', 1, 'Alternance', '12 mois', 'Audit SEO et optimisation de référencement.', 'Bac+3 Communication/Web', 'Selon barème alternance', 'Abonnement outils SEO premium, Séminaire annuel, Télétravail', 0),
(15, 'Vendeur spécialisé', 'Conseil client et gestion des stocks en magasin de sport.', 'LILLE', '2026-03-23', 5, 'Stage', '3 mois', 'Vente et conseil client en magasin spécialisé.', 'Bac Pro Vente', '623.70€ / mois', 'Réductions magasin (-20%), Primes de vente, Uniforme fourni', 0),
(16, 'Stage Cybersécurité', 'Analyse de vulnérabilités et tests d intrusion.', 'PARIS', '2026-03-23', 4, 'Stage', '6 mois', 'Réalisation de scans de vulnérabilités (Nessus), analyse de logs serveurs et aide à la rédaction de politiques de sécurité (PSSI).', 'Bac+5 spécialisation Sécurité. Connaissances en Linux, protocoles réseaux et outils de pentest. Éthique irréprochable.', '950€ / mois', 'Participation aux conférences sécurité, PC Sécurisé, Mutuelle', 0),
(17, 'Alternance Logistique', 'Optimisation de la chaîne d approvisionnement.', 'NANTES', '2026-03-24', 2, 'Alternance', '24 mois', 'Optimisation de la chaîne logistique et approvisionnement.', 'Bac+2/3 Logistique', 'Selon barème alternance', 'Restauration collective, Prime de fin d année, Prévoyance', 0),
(18, 'Designer UI/UX', 'Conception de maquettes pour une application mobile de santé.', 'LYON', '2026-03-24', 1, 'Stage', '4 mois', 'Conception de maquettes UI/UX pour mobile.', 'École de Design / Figma', '700€ / mois', 'Licence Adobe Creative Cloud, Espace coworking, Café illimité', 0),
(19, 'Chef de Projet Digital', 'Coordination entre les équipes techniques et créatives.', 'STRASBOURG', '2026-03-25', 3, 'Alternance', '12 mois', 'Coordination d équipes techniques et pilotage de projet.', 'Master Management de Projet', 'Selon barème alternance', 'Ordinateur de fonction, Smartphone pro, Forfait data', 0),
(20, 'Stage Électricien', 'Installation de systèmes domotiques sur chantiers.', 'TOULOUSE', '2026-03-25', 2, 'Stage', '3 mois', 'Installation et paramétrage de systèmes domotiques.', 'BTS Domotique', '623.70€ / mois', 'Outillage professionnel, Frais de déplacement, Panier repas', 0),
(21, 'Alternant Web Design', 'Mise à jour graphique du site institutionnel.', 'NICE', '2026-03-26', 1, 'Alternance', '12 mois', 'Mise à jour graphique et intégration Web Design.', 'Bac+2 Design graphique', 'Selon barème alternance', 'Tablette graphique, Télétravail autorisé, Flexibilité horaire', 0),
(22, 'Stage Comptabilité', 'Aide à la clôture annuelle et saisie des factures.', 'RENNES', '2026-03-26', 5, 'Stage', '6 mois', 'Aide à la clôture comptable et saisie de factures.', 'BTS Comptabilité', '680€ / mois', 'Remboursement frais réels, Prime de bilan, Chèques vacances', 0),
(23, 'Ingénieur Réseau', 'Configuration de routeurs et switchs Cisco.', 'MONTPELLIER', '2026-03-27', 4, 'Alternance', '24 mois', 'Configuration de routeurs et switchs réseaux.', 'Bac+3 Réseaux & Télécoms', 'Selon barème alternance', 'Lab réseau 24/7, Certification Cisco payée, Mutuelle', 0),
(24, 'Stage Communication', 'Rédaction de newsletters et gestion de communauté.', 'LYON', '2026-03-27', 3, 'Stage', '2 mois', 'Rédaction de newsletters et community management.', 'Bac+2 Communication', 'Gratification minimale légale', 'Chèques culture, Événements de team building, Transport 50%', 0),
(25, 'Commercial Junior', 'Prospection téléphonique et prise de rendez-vous.', 'PARIS', '2026-03-28', 5, 'Alternance', '12 mois', 'Prospection téléphonique et développement commercial.', 'Bac+2 Force de vente', 'Selon barème alternance + Primes objectifs', 'Challenge commercial mensuel, Smartphone pro, Primes non plafonnées', 0),
(26, 'Développeur Python', 'Création de scripts d automatisation pour la data.', 'BORDEAUX', '2026-03-28', 1, 'Alternance', '12 mois', 'Mise en place de la norme ISO 27001, réalisation d analyses de risques EBIOS RM et rédaction de politiques de sécurité informatique (PSSI).', 'Master Cybersécurité ou Management des SI. Esprit d analyse, rigueur rédactionnelle et bonne culture tech.', 'Selon barème alternance (850€ - 1350€)', 'PC Sécurisé, Télétravail 2j/semaine, Titres restaurant (9€), Prise en charge transport 50%', 0),
(27, 'Stage Conducteur Travaux', 'Suivi de chantier et gestion des sous-traitants.', 'GRENOBLE', '2026-03-29', 2, 'Stage', '6 mois', 'Conception de tableaux de bord interactifs sous Power BI pour le suivi des ressources et des budgets sur les chantiers en cours.', 'Bac+3/5 Informatique ou Statistiques. Maîtrise de SQL et d un outil BI (Tableau/Power BI). Curiosité métier.', '850€ / mois', 'Accès salle de sport entreprise, Ordinateur portable fourni, Remboursement Navigo 50%', 0),
(28, 'Alternance Data Science', 'Traitement de données massives avec Spark.', 'PARIS', '2026-03-29', 3, 'Alternance', '24 mois', 'Développement de nouvelles fonctionnalités sur l application mobile de suivi patient via Flutter. Maintenance et tests unitaires.', 'Bac+3 minimum en Développement Web/Mobile. Passionné par Flutter ou React Native. Sens de l expérience utilisateur (UX).', 'Selon barème alternance', 'Forfait mobile pro, Horaires flexibles, Chèques cadeaux, Café/Fruits à volonté', 0),
(29, 'Stage Assistant RH', 'Tri des CV et planification des entretiens.', 'LILLE', '2026-03-30', 5, 'Stage', '4 mois', 'Sourcing de profils pénuriques sur les jobboards, aide à la gestion administrative du personnel et suivi des dossiers stagiaires.', 'Formation RH (Bac+2/3). Excellent relationnel, sens de l organisation et discrétion professionnelle.', '623.70€ / mois (Gratification légale)', 'Restauration collective, Remboursement transport 50%, Chèques culture', 0),
(30, 'Technicien Maintenance', 'Réparation préventive sur lignes de production.', 'DIJON', '2026-03-30', 2, 'Alternance', '12 mois', 'Configuration et sécurisation des accès IAM sur AWS, automatisation des déploiements via Ansible et monitoring de l infrastructure.', 'Bac+3/4 Systèmes et Réseaux. Solides bases Linux et intérêt pour le Cloud Computing et le DevOps.', 'Selon barème alternance', 'Certification Cloud offerte, Prime de vacances, Tickets Restaurant, Smartphone pro', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pilotes`
--

CREATE TABLE `pilotes` (
  `id_pilote` int NOT NULL,
  `id_utilisateur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pilotes`
--

INSERT INTO `pilotes` (`id_pilote`, `id_utilisateur`) VALUES
(5, 5);

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `id_utilisateur` int NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Administrateur','Pilote','Etudiant') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `Utilisateurs`
--

INSERT INTO `Utilisateurs` (`id_utilisateur`, `email`, `mdp`, `role`, `nom`, `prenom`) VALUES
(4, 'deograciaskinhou200@gmail.com', '$2y$10$TSQ3f8AiaxV10YOZmLf69uvfDpphmGCY1st39VUS.pFFH7cc2Fm6q', 'Administrateur', 'Deo', 'KINHOU'),
(5, 'deograciaskinhou9@gmail.com', '$2y$10$o9j3EhEjZMzydUzEEseNc.VHRtB32NUqQh3NEytA7OKN0eEhmXmt2', 'Pilote', 'Oswald', 'HENRI'),
(8, 'deokinhou9@gmail.com', '$2y$10$Bj.m43oSe15NWe6qwcqDCuBBj51dU3Pz0Cw.IJq.dvw9Yn8blDoeq', 'Etudiant', 'Thomas', 'DUPINT');

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `id_whishlist` int NOT NULL,
  `date_ajout` date NOT NULL,
  `id_utilisateur` int NOT NULL,
  `id_offre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`id_whishlist`, `date_ajout`, `id_utilisateur`, `id_offre`) VALUES
(2, '2026-03-29', 8, 1),
(3, '2026-03-29', 8, 14);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id_addresse`),
  ADD KEY `adresses_id_entreprise_FK` (`id_entreprise`);

--
-- Index pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id_candidature`),
  ADD UNIQUE KEY `uq_candidature_utilisateur_offre` (`id_utilisateur`,`id_offre`),
  ADD KEY `candidatures_offre_fk` (`id_offre`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id_entreprise`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id_etudiant`),
  ADD KEY `etudiants_pilotes_FK` (`id_pilote`);

--
-- Index pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id_evaluation`),
  ADD KEY `fk_entreprise` (`id_entreprise`),
  ADD KEY `fk_pilote` (`id_utilisateur`);

--
-- Index pour la table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id_offre`),
  ADD KEY `offres_id_entreprise_FK` (`id_entreprise`);

--
-- Index pour la table `pilotes`
--
ALTER TABLE `pilotes`
  ADD PRIMARY KEY (`id_pilote`);

--
-- Index pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id_whishlist`),
  ADD KEY `wishlist_etudiants_FK` (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id_addresse` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id_candidature` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id_entreprise` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id_evaluation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `offres`
--
ALTER TABLE `offres`
  MODIFY `id_offre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `id_utilisateur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id_whishlist` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD CONSTRAINT `adresses_id_entreprise_FK` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE CASCADE;

--
-- Contraintes pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `candidatures_offre_fk` FOREIGN KEY (`id_offre`) REFERENCES `offres` (`id_offre`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidatures_utilisateur_fk` FOREIGN KEY (`id_utilisateur`) REFERENCES `Utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD CONSTRAINT `etudiants_pilotes_FK` FOREIGN KEY (`id_pilote`) REFERENCES `pilotes` (`id_pilote`),
  ADD CONSTRAINT `etudiants_Utilisateurs_FK` FOREIGN KEY (`id_etudiant`) REFERENCES `Utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `fk_entreprise` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pilote` FOREIGN KEY (`id_utilisateur`) REFERENCES `pilotes` (`id_pilote`) ON DELETE CASCADE;

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `offres_id_entreprise_FK` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE CASCADE;

--
-- Contraintes pour la table `pilotes`
--
ALTER TABLE `pilotes`
  ADD CONSTRAINT `pilotes_id_utilisateur_FK` FOREIGN KEY (`id_pilote`) REFERENCES `Utilisateurs` (`id_utilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_etudiants_FK` FOREIGN KEY (`id_utilisateur`) REFERENCES `etudiants` (`id_etudiant`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
