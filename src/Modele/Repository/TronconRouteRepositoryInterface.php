<?php

namespace Explore\Modele\Repository;

use Explore\Modele\DataObject\AbstractDataObject;
use Explore\Modele\DataObject\TronconRoute;

interface TronconRouteRepositoryInterface
{
    public function construireDepuisTableau(array $noeudRoutierTableau): TronconRoute;

    public function supprimer(string $valeurClePrimaire): bool;

    public function mettreAJour(AbstractDataObject $object): void;

    public function ajouter(AbstractDataObject $object): bool;
    /**
     * @param int|string $limit Nombre de réponses ("ALL" pour toutes les réponses)
     * @return AbstractDataObject[]
     */
    public function recuperer($limit = 200): array;

    /**
     * @param array $critereSelection ex: ["nomColonne" => valeurDeRecherche]
     * @return AbstractDataObject[]
     */
    public function recupererPar(array $critereSelection, $limit = 200): array;

    public function recupererParClePrimaire(string $valeurClePrimaire): ?AbstractDataObject;

}