<?php

namespace Explore\Controleur;

use Explore\Lib\MessageFlash;
use Explore\Lib\PlusCourtChemin;
use Explore\Modele\DataObject\NoeudCommune;
use Explore\Modele\Repository\NoeudCommuneRepository;
use Explore\Service\Exception\ServiceException;
use Explore\Service\NoeudCommuneServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use http\Env\Request;

class ControleurNoeudCommune extends ControleurGenerique
{

    private NoeudCommuneServiceInterface $noeudCommuneService;

    public function __construct(NoeudCommuneServiceInterface $noeudCommuneService)
    {
        $this->noeudCommuneService = $noeudCommuneService;
    }

    public static function afficherErreur($errorMessage = "", $controleur = ""): Response
    {
        return parent::afficherErreur($errorMessage, "noeudCommune");
    }

    public function afficherListe(): Response
    {
        try {
            $noeudsCommunes = $this->noeudCommuneService->recupererListeNoeudsCommunes();
        } catch (ServiceException $e) {
            MessageFlash::ajouter('danger', $e->getMessage());
            return ControleurNoeudCommune::rediriger("plusCourt");
        }
        return ControleurNoeudCommune::afficherTwig('noeudCommune/liste.html.twig', [
            "noeudsCommunes" => $noeudsCommunes,
            "pagetitle" => "Liste des Noeuds Routiers",
        ]);
    }

    //On à combiner detail et liste
    // public function afficherDetail(): Response
    // {
    //     $noeud = null;
    //     try {
    //         $gid = $_REQUEST['gid'] ?? null;
    //         $noeud = $this->noeudCommuneService->afficherDetailNoeudCommune($gid);
    //     } catch (ServiceException $e) {
    //         MessageFlash::ajouter('danger', $e->getMessage());
    //         return ControleurNoeudCommune::rediriger("afficherListe");
    //     }

    //     return ControleurNoeudCommune::afficherVue('vueGenerale.php', [
    //         "noeudCommune" => $noeud,
    //         "pagetitle" => "Détail de la noeudCommune",
    //         "cheminVueBody" => "noeudCommune/detail.php"
    //     ]);
    // }

    public function plusCourtChemin($depart = null, $arrivee = null): Response
    {
        $nomCommuneDepart = $_REQUEST["nomCommuneDepart"] ?? null;
        $nomCommuneArrivee = $_REQUEST["nomCommuneArrivee"] ?? null;


        try {
            /*MessageFlash::ajouter('success', "
            Le plus court chemin entre $nomCommuneDepart et  $nomCommuneArrivee mesure " .  $parametres["distance"] . " km.
            ");*/

            return ControleurNoeudCommune::afficherVue('noeudCommune/plusCourtChemin.html.twig', [
                "pagetitle" => "Explore",
                $this->noeudCommuneService->plusCourtCheminNC($nomCommuneDepart, $nomCommuneArrivee),
            ]);
        } catch (ServiceException $e) {
            MessageFlash::ajouter('danger', $e->getMessage());
            return ControleurNoeudCommune::afficherTwig('noeudCommune/plusCourtChemin.html.twig', [
                "pagetitle" => "Explore",
            ]);
        }
    }


    public function requeteVille($ville): JsonResponse
    {
        try {
            $json = $this->noeudCommuneService->afficherAutocompletion($ville);
            return new JsonResponse($json);
        } catch (ServiceException $se) {
            return new JsonResponse(["error" => $se->getMessage()], $se->getCode());
        }

        /*return ControleurNoeudCommune::afficherVue('noeudCommune/requeteVille.php', [
            "tab" => $tab,
            "pagetitle" => "requeteVille"
        ]);*/
    }
}
