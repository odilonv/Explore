<?php

namespace Explore\Lib;

class Vector
{
    public float $x;
    public float $y;

    public function __construct(float $x = 0, float $y = 0, bool $normalized = false)
    {
        if (!$normalized) {
            $this->x = $x;
            $this->y = $y;
        } else {
            $longueur = sqrt($x * $x + $y * $y);
            $this->x = $x / $longueur;
            $this->y = $y / $longueur;
        }
    }

    public function normalized()
    {
        $longueur = sqrt($this->x * $this->x + $this->y * $this->y);
        return new Vector($this->x / $longueur, $this->y / $longueur);
    }

    public function normalize()
    {
        $longueur = sqrt($this->x * $this->x + $this->y * $this->y);

        $this->x = $this->x / $longueur;
        $this->y = $this->y / $longueur;
    }
}


/*
 * un noeud c'est l'adresse de ses voisins, la valeur pour les join
 * définir la valeur du noeud: sa distance du départ + sa distance de l'arrivée
 * on la calcul dès qu'un voisin est sélectionné
 * un noeud a 4 états: complètement électionné, vérifié, en cours et pas vérifié
 *  - vérifié ne nécessite plus de mise a jour sur sa valeur
 *  - pas vérifié entrera dans la boucle si il est voisin de qqn qui vient d'être sélectionné
 *
 * on met a jour la valeur d'un noeud dès qu'un voisin est sélectionné
 *
 */
