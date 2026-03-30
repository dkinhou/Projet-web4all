<?php

class Pagination {
    private array $elements;      // Tous les éléments
    private int $parPage;         // Nombre d'éléments par page
    private int $pageActuelle;    // La page où on est
    private int $totalElements;   // Nombre total d'éléments
    private int $totalPages;      // Nombre total de pages

    public function __construct(array $elements, int $parPage, int $pageActuelle) {
        $this->elements      = $elements;
        $this->parPage       = $parPage;
        $this->totalElements = count($elements);

        // On calcule le nombre total de pages
        // ceil() arrondit au supérieur
        // Ex: 25 éléments / 10 par page = 2.5 → ceil = 3 pages
        $this->totalPages = (int) ceil($this->totalElements / $this->parPage);

        // On s'assure que la page demandée existe
        // Si quelqu'un tape ?page=999, on le ramène à la dernière page
        $this->pageActuelle = max(1, min($pageActuelle, $this->totalPages));
    }

    // Retourne les éléments de la page courante
    // C'est ici qu'on utilise array_slice comme dit dans le prosit
    public function getElements(): array {
        // On calcule l'index de début
        $debut = ($this->pageActuelle - 1) * $this->parPage;

        // array_slice découpe le tableau
        return array_slice($this->elements, $debut, $this->parPage);
    }

    // Retourne le numéro de la page actuelle
    public function getPageActuelle(): int {
        return $this->pageActuelle;
    }

    // Retourne le nombre total de pages
    public function getTotalPages(): int {
        return $this->totalPages;
    }

    // Retourne le nombre total d'éléments
    public function getTotalElements(): int {
        return $this->totalElements;
    }

    // Est-ce qu'il y a une page précédente ?
    public function hasPrecedent(): bool {
        return $this->pageActuelle > 1;
    }

    // Est-ce qu'il y a une page suivante ?
    public function hasSuivant(): bool {
        return $this->pageActuelle < $this->totalPages;
    }

    // Numéro de la page précédente
    public function getPagePrecedente(): int {
        return $this->pageActuelle - 1;
    }

    // Numéro de la page suivante
    public function getPageSuivante(): int {
        return $this->pageActuelle + 1;
    }
}
