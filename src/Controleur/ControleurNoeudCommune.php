<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Lib\PlusCourtChemin;
use App\PlusCourtChemin\Modele\DataObject\NoeudCommune;
use App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;




class ControleurNoeudCommune extends ControleurGenerique
{

    public static function afficherErreur($errorMessage = "", $controleur = ""): Response
    {
        return parent::afficherErreur($errorMessage, "noeudCommune");
    }

    public static function afficherListe(): Response
    {
        $noeudsCommunes = (new NoeudCommuneRepository())->recuperer();     //appel au modèle pour gerer la BD
        return ControleurNoeudCommune::afficherVue('vueGenerale.php', [
            "noeudsCommunes" => $noeudsCommunes,
            "pagetitle" => "Liste des Noeuds Routiers",
            "cheminVueBody" => "noeudCommune/liste.php"
        ]);
    }

    public static function afficherDetail(): RedirectResponse
    {
        if (!isset($_REQUEST['gid'])) {
            MessageFlash::ajouter("danger", "Immatriculation manquante.");
             return ControleurNoeudCommune::rediriger("noeudCommune", "afficherListe");
        }

        $gid = $_REQUEST['gid'];
        $noeudCommune = (new NoeudCommuneRepository())->recupererParClePrimaire($gid);

        if ($noeudCommune === null) {
            MessageFlash::ajouter("warning", "gid inconnue.");
            return ControleurNoeudCommune::rediriger("noeudCommune", "afficherListe");
        }

        return ControleurNoeudCommune::afficherVue('vueGenerale.php', [
            "noeudCommune" => $noeudCommune,
            "pagetitle" => "Détail de la noeudCommune",
            "cheminVueBody" => "noeudCommune/detail.php"
        ]);
    }

    public static function plusCourtChemin($depart = null, $arrivee = null): Response
    {
        $parametres = [
            "pagetitle" => "Explore",
            "cheminVueBody" => "noeudCommune/plusCourtChemin.php",
        ];

        if (isset($_POST['nomCommuneDepart']) && isset($_POST['nomCommuneArrivee'])) {
            $nomCommuneDepart = $_POST["nomCommuneDepart"];
            $nomCommuneArrivee = $_POST["nomCommuneArrivee"];

            $noeudCommuneRepository = new NoeudCommuneRepository();
            /** @var NoeudCommune $noeudCommuneDepart */
            $noeudCommuneDepart = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart])[0];
            /** @var NoeudCommune $noeudCommuneArrivee */
            $noeudCommuneArrivee = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee])[0];

            $noeudRoutierRepository = new NoeudRoutierRepository();
            $noeudRoutierDepartGid = $noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0]->getGid();
            $noeudRoutierArriveeGid = $noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
            ])[0]->getGid();

            $pcc = new PlusCourtChemin($noeudRoutierDepartGid, $noeudRoutierArriveeGid);

            // $distance = $pcc->calculer();

            $dernierNoeud = $pcc->calculer3();
            $multiline = [];
            foreach ($dernierNoeud->refaireChemin() as $noeud){
                $coords = $noeud->getCoords();
                $multiline[] = ['lat'=>$coords['latitude'], 'lng'=>$coords['longitude']];
            }
            $distance = $dernierNoeud->getDistanceDebut();

            $parametres["nomCommuneDepart"] = $nomCommuneDepart;
            $parametres["nomCommuneArrivee"] = $nomCommuneArrivee;
            $parametres["distance"] = $distance;

        }

        return ControleurNoeudCommune::afficherVue('vueGenerale.php', $parametres);
    }

    public static function requetePlusCourt($depart, $arrivee){
        $parametres = [];

        $nomCommuneDepart = $depart;
        $nomCommuneArrivee = $arrivee;

        $noeudCommuneRepository = new NoeudCommuneRepository();
        /** @var NoeudCommune $noeudCommuneDepart */
        $noeudCommuneDepart = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart])[0];
        /** @var NoeudCommune $noeudCommuneArrivee */
        $noeudCommuneArrivee = $noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee])[0];

        $noeudRoutierRepository = new NoeudRoutierRepository();
        $noeudRoutierDepartGid = $noeudRoutierRepository->recupererPar([
            "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
        ])[0]->getGid();
        $noeudRoutierArriveeGid = $noeudRoutierRepository->recupererPar([
            "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
        ])[0]->getGid();

        $pcc = new PlusCourtChemin($noeudRoutierDepartGid, $noeudRoutierArriveeGid);

        // $distance = $pcc->calculer();

        $dernierNoeud = $pcc->calculer3();
        $multiline = [];
        foreach ($dernierNoeud->refaireChemin() as $noeud){
            $coords = $noeud->getCoords();
            $multiline[] = ['lat'=>$coords['latitude'], 'lng'=>$coords['longitude']];
        }
        $distance = $dernierNoeud->getDistanceDebut();

        $parametres['multiline'] = $multiline;
        $parametres["nomCommuneDepart"] = $nomCommuneDepart;
        $parametres["nomCommuneArrivee"] = $nomCommuneArrivee;
        $parametres["distance"] = $distance;

        echo json_encode($parametres);
    }
}
