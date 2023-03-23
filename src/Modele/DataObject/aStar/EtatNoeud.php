<?php

namespace App\PlusCourtChemin\Modele\DataObject\aStar;

enum EtatNoeud : string{
    case VERIFIE = 'verifie';
    case POSSIBLE = 'possible';
    case PAUSE = 'pause';
}
