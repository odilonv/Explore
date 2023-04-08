<?php

namespace Explore\Modele\DataObject\aStar;

enum EtatNoeud : string{
    case VERIFIE = 'verifie';
    case POSSIBLE = 'possible';
    case PAUSE = 'pause';
}
