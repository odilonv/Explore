<?php

namespace Explore\Lib;

use Explore\Modele\DataObject\aStar\NoeudStar;

interface PlusCourtCheminInterface
{
    public function calculer3(): ?NoeudStar;
}