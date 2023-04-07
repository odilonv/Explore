<?php

namespace Explore\Service;

use Explore\Service\Exception\ServiceException;

interface UtilisateurServiceInterface
{
    /**
     * @throws ServiceException
     */
    public function creerUtilisateur($login, $password, $adresseMail, $profilePictureData);

    /**
     * @throws ServiceException
     */
    public function recupererListeUtilisateur($autoriserNull = true);

    /**
     * @throws ServiceException
     */
    public function recupererUtilisateur($idUtilisateur, $autoriserNull = true);

    /**
     * @throws ServiceException
     */
    public function connecterUtilisateur($login, $password);

    /**
     * @throws ServiceException
     */
    public function deconnecterUtilisateur();
}