<?php

namespace Explore\Controleur;

use Explore\Lib\MessageFlash;
use Explore\Service\Exception\ServiceException;
use Explore\Service\NoeudCommuneService;
use Explore\Service\NoeudCommuneServiceInterface;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ControleurNoeudCommuneAPI
{
    private NoeudCommuneServiceInterface $noeudCommuneService;

    public function __construct(NoeudCommuneServiceInterface $noeudCommuneService)
    {
        $this->noeudCommuneService = $noeudCommuneService;
    }

    public function getPlusCourt(string $nomCommuneDepart, string $nomCommuneArrivee)
    {
        try {
            $reponseJSON = $this->noeudCommuneService->requetePlusCourt($nomCommuneDepart, $nomCommuneArrivee);
            return new JsonResponse($reponseJSON);
        } catch (ServiceException $se) {
            return new JsonResponse(["error" => $se->getMessage()], $se->getCode());
        }
    }
}
