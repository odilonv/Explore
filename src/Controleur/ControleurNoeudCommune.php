<?php

namespace Explore\Controleur;


use Explore\Lib\MessageFlash;
use Explore\Lib\PlusCourtChemin;
use Explore\Modele\DataObject\NoeudCommune;
use Explore\Modele\Repository\NoeudCommuneRepository;
use Explore\Service\Exception\ServiceException;
use Explore\Service\NoeudCommuneServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;




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

    public function afficherListe() :  Response
    {
        try {
            $noeudsCommunes = $this->noeudCommuneService->recupererListeNoeudsCommunes();
        } catch (ServiceException $e) {
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurNoeudCommune::rediriger("plusCourt");
        }
        return ControleurNoeudCommune::afficherVue('vueGenerale.php', [
            "noeudsCommunes" => $noeudsCommunes,
            "pagetitle" => "Liste des Noeuds Routiers",
            "cheminVueBody" => "noeudCommune/liste.php"
        ]);
    }

    public function afficherDetail(): Response
    {
        $noeud=null;
        try{
            $gid = $_REQUEST['gid'] ?? null;
            $noeud = $this->noeudCommuneService->afficherDetailNoeudCommune($gid);

        }
        catch (ServiceException $e){
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurNoeudCommune::rediriger("afficherListe");
        }

        return ControleurNoeudCommune::afficherVue('vueGenerale.php', [
        "noeudCommune" => $noeud,
        "pagetitle" => "Détail de la noeudCommune",
        "cheminVueBody" => "noeudCommune/detail.php"
        ]);

    }

    public function plusCourtChemin($depart = null, $arrivee = null): Response
    {
        $nomCommuneDepart = $_REQUEST["nomCommuneDepart"] ?? null;
        $nomCommuneArrivee = $_REQUEST["nomCommuneArrivee"] ?? null;

        $parametres = [
            "pagetitle" => "Explore",
            "cheminVueBody" => "noeudCommune/plusCourtChemin.php",
        ];

        try{
            $parametres += $this->noeudCommuneService->plusCourtCheminNC($nomCommuneDepart, $nomCommuneArrivee);

            return ControleurNoeudCommune::afficherVue('vueGenerale.php', $parametres);
        }
        catch(ServiceException $e) {
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurNoeudCommune::afficherVue('vueGenerale.php', $parametres);
        }
    }

    public function requetePlusCourt($depart, $arrivee){
        try {
            $parametres= $this->noeudCommuneService->requetePlusCourt($depart, $arrivee);
            echo json_encode($parametres);
        }
        catch (ServiceException $e){
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurUtilisateur::rediriger("plusCourt");
        }
    }
}
