<?php

namespace Explore\Lib\vieux\aStar;

enum EtatNoeud : string{
    case VERIFIE = 'verifie';
    case POSSIBLE = 'possible';
    case PAUSE = 'pause';
}
