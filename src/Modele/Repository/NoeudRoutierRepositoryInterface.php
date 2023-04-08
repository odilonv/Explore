<?php

namespace Explore\Modele\Repository;

use Explore\Lib\vieux\CacheNR;
use Explore\Modele\DataObject\AbstractDataObject;
use Explore\Modele\DataObject\aStar\QueueStar;
use Explore\Modele\DataObject\NoeudRoutier;

interface NoeudRoutierRepositoryInterface
{
    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudRoutier;

    public function supprimer(string $valeurClePrimaire): bool;

    public function mettreAJour(AbstractDataObject $object): void;

    public function ajouter(AbstractDataObject $object): bool;

    /**
     * Renvoie le tableau des voisins d'un noeud routier
     *
     * pour chaque voisin, le gid du voisin est une clé associé au gid du troncon ainsi que la longueur
     * [ gidVoisin => [gidTR, longueur]]
     *
     * @param int $noeudRoutierGid
     * @return String[][]
     **/
    public function getVoisins(int $noeudRoutierGid): array;

    /**
     * @param string $gidCentre
     * @param float $range
     * @return array
     * range est le rayon en metre
     * pour chaque voisin, le gid du voisin est une clé associé au gid du troncon ainsi que la longueur
     * [ gidVoisin => [gidNR, gidTR, longueur]]
     */
    public function getInRange(string $geomCentre, float $range): CacheNR;


    public function genererChaineZone(array $startPoint, array $endPoint);

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

    public function getForStar(string $gidDep, string $gidArrivee, QueueStar $starQueue);

}