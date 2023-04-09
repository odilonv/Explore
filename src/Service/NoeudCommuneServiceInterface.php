<?php

namespace Explore\Service;

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
    public function requetePlusCourt($depart, $arrivee);

    /**
     * @throws ServiceException
     */
    public function afficherAutocompletion($ville);
}
