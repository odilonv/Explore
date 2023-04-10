<?php

namespace Explore\Test;

use Explore\Lib\PlusCourtChemin;
use Explore\Modele\DataObject\NoeudCommune;
use Explore\Modele\Repository\NoeudCommuneRepositoryInterface;
use Explore\Modele\Repository\NoeudRoutierRepositoryInterface;
use Explore\Service\Exception\ServiceException;
use Explore\Service\NoeudCommuneService;
use Explore\Service\NoeudCommuneServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class NoeudCommuneTest extends TestCase
{
    private NoeudCommuneService $service;

    private NoeudCommuneRepositoryInterface $noeudCommuneRepositoryMock;
    private NoeudRoutierRepositoryInterface $noeudRoutierRepositoryMock;
    private PlusCourtChemin $plusCourtCheminMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->noeudRoutierRepositoryMock = $this->createMock(NoeudRoutierRepositoryInterface::class);
        $this->noeudCommuneRepositoryMock = $this->createMock(NoeudCommuneRepositoryInterface::class);
        $this->plusCourtCheminMock = $this->createMock(PlusCourtChemin::class);
        $this->service = new NoeudCommuneService($this->noeudCommuneRepositoryMock, $this->noeudRoutierRepositoryMock);
    }

    /**
     * @throws ServiceException
     */
    public function testNombreNoeudsCommunes()
    {
        //Faux Noeuds
        $fakeNoeuds = [new NoeudCommune(0,0, "Test1", 0 ),
            new NoeudCommune(1, 1, "Test2", 1)];
        //On configure notre faux repository pour qu'il renvoie nos noeuds définis ci-dessus
        $this->noeudCommuneRepositoryMock->method("recuperer")->willReturn($fakeNoeuds);
        //Test
        $this->assertCount(2, $this->service->recupererListeNoeudsCommunes());
    }

    public function testListeNoeudsVide()
    {
        $this->expectException(ServiceException::class);
        $this->noeudCommuneRepositoryMock->method("recuperer")->willReturn([]);
        $this->expectExceptionMessage('Aucun noeud n\' est disponible !');
        $this->assertEmpty($this->service->recupererListeNoeudsCommunes());
    }

    public function testAfficherDetailNoeudCommuneGidInconnue(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Immatriculation manquante");
        $this->service->afficherDetailNoeudCommune(null);
    }
    public function testAfficherDetailNoeudCommuneNonRepertorie(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Noeud commune non repertorié.");
        $this->service->afficherDetailNoeudCommune(-1);
    }

    public function testAfficherAutocompletionVilleInexistante()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Ville introuvable");
        $this->service->afficherAutocompletion(null);
    }

    public function testRequetePlusCourtPtDeDepartOuArriveeNull(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("départ ou arrivée inconnue.");
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->service->requetePlusCourt(null, "Agde");
        $this->service->requetePlusCourt("Agde", null);
        $this->service->requetePlusCourt(null, null);
    }

    public function testRequetePlusCourtPtDepartNonRepertorie(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("La ville de départ n'existe pas");
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->service->requetePlusCourt("YoLesPotes", "Agde");
    }
    public function testRequetePlusCourtPtArriveeNonRepertorie(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("La ville d'arrivée n'existe pas");
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->service->requetePlusCourt("Agde", "NinjaSQL");
    }

    public function testRequetePlusCourtDernierNoeudNull(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Le trajet est impossible");
        $this->expectExceptionCode(400);

        $this->plusCourtCheminMock->method("calculer3")->willReturn(null);

        $this->service->requetePlusCourt("Agde", "Montpellier");
    }









/*
    public function testCreerPublicationTropGrande()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Le message ne peut pas dépasser 250 caractères!");
        $this->service->creerPublication('1', str_repeat("b", 251));
    }

    /**
     * @throws ServiceException
     * @throws \Exception

    public function testNombrePublicationsUtilisateur()
    {

        $this->publicationRepositoryMock->method("getAllFrom")->willReturn([0, 0, 0]);
        $this->utilisateurRepositoryMock->method("get")->willReturn(new Utilisateur());

        $this->assertCount(3, $this->service->recupererPublicationsUtilisateur(3));
    }

    public function testNombrePublicationsUtilisateurInexistant()
    {

        $this->publicationRepositoryMock->method("getAllFrom")->willReturn([]);
        $this->assertEquals($this->publicationRepositoryMock->getAllFrom(-1), 0);
    }*/


}