<?php

namespace Explore\Service;

use Explore\Modele\DataObject\NoeudCommune;
use Explore\Service\Exception\ServiceException;

interface NoeudCommuneServiceInterface
{
    /**
     * @throws ServiceException
     */
    public function recupererListeNoeudsCommunes($autoriserNull = true);

    /**
     * @throws ServiceException
     */
    public function plusCourtCheminNC($nomCommuneDepart, $nomCommuneArrivee);

    /**
     * @throws ServiceException
     */
    public function afficherDetailNoeudCommune($gid);

    /**
     * @throws ServiceException
     */
    public function requetePlusCourt($nomCommuneDepart, $nomCommuneArrivee);

    /**
     * @throws ServiceException
     */
    public function afficherAutocompletion($ville);

    /**
     * @throws ServiceException
     */
    public function getNearCoord($lat, $lng);
}
