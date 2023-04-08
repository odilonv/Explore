<?php

namespace Explore\Service;

use Explore\Lib\PlusCourtChemin;
use Explore\Modele\DataObject\NoeudCommune;
use Explore\Modele\DataObject\NoeudRoutier;
use Explore\Modele\Repository\NoeudCommuneRepository;
use Explore\Modele\Repository\NoeudCommuneRepositoryInterface;
use Explore\Modele\Repository\NoeudRoutierRepository;
use Explore\Modele\Repository\NoeudRoutierRepositoryInterface;
use Explore\Service\Exception\ServiceException;

class NoeudCommuneService implements NoeudCommuneServiceInterface
{
    private NoeudCommuneRepositoryInterface $noeudCommuneRepository;
    private NoeudRoutierRepositoryInterface $noeudRoutierRepository;


    public function __construct(
        NoeudCommuneRepositoryInterface $noeudCommuneRepository,
        NoeudRoutierRepositoryInterface $noeudRoutierRepository
    )
    {
        $this->noeudCommuneRepository = $noeudCommuneRepository;
        $this->noeudRoutierRepository = $noeudRoutierRepository;
    }


    /**
     * @throws ServiceException
     */
    public function recupererListeNoeudsCommunes($autoriserNull = true)
    {
        $noeuds = $this->noeudCommuneRepository->recuperer();
        if(!$autoriserNull && $noeuds!=null) {
            throw new ServiceException('Aucun noeuds n\' est disponible n\'existe pas !');
        }
        else{
            return $noeuds;
        }
    }

    /**
     * @throws ServiceException
     */
    public function plusCourtCheminNC($nomCommuneDepart, $nomCommuneArrivee){
        if ($nomCommuneDepart!=null && $nomCommuneArrivee!=null) {

            $noeudCommuneDepart = $this->noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart])[0];
            $noeudCommuneArrivee = $this->noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee])[0];

            echo 'lalalalala' . $noeudCommuneArrivee->getId_nd_rte();

            $noeudRoutierDepartGid = $this->noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0]->getGid();

            echo 'test' . $noeudRoutierDepartGid . 'bouuuuuuuuuuuuuuuuuuuuu';

            $noeudRoutierArriveeGid = $this->noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
            ])[0]->getGid();

            echo $noeudRoutierArriveeGid;
            echo $noeudRoutierDepartGid;


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

            return $parametres;

        }
        else{
            throw new ServiceException('Veuillez renseigner un point de départ et un point d\'arrivée');
        }
    }

    /**
     * @throws ServiceException
     */
    public function afficherDetailNoeudCommune($gid){
        if($gid==null){
            throw new ServiceException('Immatriculation manquante');
        }
        else{
            $noeudCommune = $this->noeudCommuneRepository->recupererParClePrimaire($gid);
            if($noeudCommune==null){
                throw new ServiceException('gid inconnue.');
            }
            else{
                return $noeudCommune;
            }
        }
    }

    /**
     * @throws ServiceException
     */
    public function requetePlusCourt($depart, $arrivee){
        if($depart!=null && $arrivee!=null){
            $parametres = [];

            $nomCommuneDepart = $depart;
            $nomCommuneArrivee = $arrivee;

            /** @var NoeudCommune $noeudCommuneDepart */
            $noeudCommuneDepart = $this->noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart])[0];
            /** @var NoeudCommune $noeudCommuneArrivee */
            $noeudCommuneArrivee = $this->noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee])[0];

            $noeudRoutierDepartGid = $this->noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0]->getGid();
            $noeudRoutierArriveeGid = $this->noeudRoutierRepository->recupererPar([
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

            return $parametres;
        }
        else{
            throw new ServiceException('départ ou arrivée inconnue.');
        }

    }


}