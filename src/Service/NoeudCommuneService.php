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
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\isNull;

class NoeudCommuneService implements NoeudCommuneServiceInterface
{
    private NoeudCommuneRepositoryInterface $noeudCommuneRepository;
    private NoeudRoutierRepositoryInterface $noeudRoutierRepository;


    public function __construct(
        NoeudCommuneRepositoryInterface $noeudCommuneRepository,
        NoeudRoutierRepositoryInterface $noeudRoutierRepository,
    ) {
        $this->noeudCommuneRepository = $noeudCommuneRepository;
        $this->noeudRoutierRepository = $noeudRoutierRepository;
    }

    /**
     * @throws ServiceException
     */
    public function recupererListeNoeudsCommunes($autoriserNull = true)
    {
        $noeuds = $this->noeudCommuneRepository->recuperer();
        if (!$autoriserNull || $noeuds == null) {
            throw new ServiceException('Aucun noeud n\' est disponible !');
        } else {
            return $noeuds;
        }
    }

    /**
     * @throws ServiceException
     */
    public function afficherDetailNoeudCommune($gid, $autoriserNull = true)
    {
        if (!$autoriserNull || $gid == null) {
            throw new ServiceException('Immatriculation manquante');
        } else {
            $noeudCommune = $this->noeudCommuneRepository->recupererParClePrimaire($gid);
            if ($noeudCommune == null) {
                throw new ServiceException('Noeud commune non repertorié.');
            } else {
                return $noeudCommune;
            }
        }
    }

    /**
     * @throws ServiceException
     */
    public function afficherAutocompletion($ville)
    {
        if ($ville != null) {
            return $this->noeudCommuneRepository->getCommune($ville);
        } else {
            throw new ServiceException('Ville introuvable');
        }
    }


    /**
     * @throws ServiceException
     */
    public function plusCourtCheminNC($nomCommuneDepart, $nomCommuneArrivee)
    {
        if ($nomCommuneDepart != null && $nomCommuneArrivee != null) {

            $noeudCommuneDepart = $this->noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart])[0] ?? null;
            $noeudCommuneArrivee = $this->noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee])[0] ?? null;
            if ($noeudCommuneDepart == null || $noeudCommuneArrivee == null) {
                throw new ServiceException('Veuillez renseigner un point de départ et un point d\'arrivée valide', 400);
            }

            $noeudRoutierDepartGid = $this->noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
            ])[0]->getGid();


            $noeudRoutierArriveeGid = $this->noeudRoutierRepository->recupererPar([
                "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
            ])[0]->getGid();


            $pcc = new PlusCourtChemin($noeudRoutierDepartGid, $noeudRoutierArriveeGid, $this->noeudRoutierRepository);

            // $distance = $pcc->calculer();

            $dernierNoeud = $pcc->calculer3();
            if ($dernierNoeud == null) {
                $distance = -1;
            } else {
                $distance = $dernierNoeud->getDistanceDebut();
            }

            $parametres["nomCommuneDepart"] = $nomCommuneDepart;
            $parametres["nomCommuneArrivee"] = $nomCommuneArrivee;
            $parametres["distance"] = $distance;

            return $parametres;
        } else {
            throw new ServiceException('Veuillez renseigner un point de départ et un point d\'arrivée',400);
        }
    }

    /**
     * @throws ServiceException
     */
    public function requetePlusCourt($nomCommuneDepart, $nomCommuneArrivee)
    {
        if (is_null($nomCommuneDepart) || is_null($nomCommuneArrivee)) {
            throw new ServiceException('départ ou arrivée inconnue.', Response::HTTP_NOT_FOUND);
        }
        $resultat = [];

        /** @var NoeudCommune $noeudCommuneDepart */
        $dep = $this->noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneDepart]);
        if(sizeof($dep)==0){
            throw new ServiceException("La ville de départ n'existe pas", Response::HTTP_NOT_FOUND);
        }
        $noeudCommuneDepart = $dep[0];
        /** @var NoeudCommune $noeudCommuneArrivee */
        $arr = $this->noeudCommuneRepository->recupererPar(["nom_comm" => $nomCommuneArrivee]);
        if(sizeof($arr)==0){
            throw new ServiceException("La ville d'arrivée n'existe pas", Response::HTTP_NOT_FOUND);
        }
        $noeudCommuneArrivee = $arr[0];

        if (is_null($noeudCommuneDepart) || is_null($noeudCommuneArrivee)) {
            throw new ServiceException('départ ou arrivée inconnue.', Response::HTTP_NOT_FOUND);
        }
        $noeudRoutierDepartGid = $this->noeudRoutierRepository->recupererPar([
            "id_rte500" => $noeudCommuneDepart->getId_nd_rte()
        ])[0]->getGid();
        $noeudRoutierArriveeGid = $this->noeudRoutierRepository->recupererPar([
            "id_rte500" => $noeudCommuneArrivee->getId_nd_rte()
        ])[0]->getGid();

        $pcc = new PlusCourtChemin($noeudRoutierDepartGid, $noeudRoutierArriveeGid, $this->noeudRoutierRepository);
        $dernierNoeud = $pcc->calculer3();
        if($dernierNoeud===null){
            throw new ServiceException("Le trajet est impossible", 400);
        }
        $multiline = [];
        foreach ($dernierNoeud->refaireChemin() as $noeud) {
            $coords = $noeud->getCoords();
            $multiline[] = ['lat' => $coords['latitude'], 'lng' => $coords['longitude']];
        }
        $distance = $dernierNoeud->getDistanceDebut();

        $resultat['multiline'] = $multiline;
        $resultat["nomCommuneDepart"] = $nomCommuneDepart;
        $resultat["nomCommuneArrivee"] = $nomCommuneArrivee;
        $resultat["distance"] = $distance;

        return $resultat;
    }

    /**
     * @throws ServiceException
     */
    public function getNearCoord($lat, $lng){
        $val= $this->noeudCommuneRepository->recupererParProximite($lat, $lng);
        if($val==null){
            throw new ServiceException("Commune introuvable", 400);
        }
        else{
            return ['nomCommune' => $val];
        }

    }
}
