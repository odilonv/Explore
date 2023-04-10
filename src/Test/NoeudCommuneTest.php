<?php

namespace Explore\Test;

use Explore\Lib\PlusCourtChemin;
use Explore\Lib\PlusCourtCheminInterface;
use Explore\Modele\DataObject\aStar\NoeudStar;
use Explore\Modele\DataObject\NoeudCommune;
use Explore\Modele\DataObject\NoeudRoutier;
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
    private PlusCourtCheminInterface $plusCourtCheminMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->noeudRoutierRepositoryMock = $this->createMock(NoeudRoutierRepositoryInterface::class);
        $this->noeudCommuneRepositoryMock = $this->createMock(NoeudCommuneRepositoryInterface::class);
        $this->plusCourtCheminMock = $this->createMock(PlusCourtCheminInterface::class);
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
        $fakeNoeudCom = [new NoeudCommune(0,0, "Test1", 0 )];

        $this->noeudCommuneRepositoryMock->expects($this->exactly(2))
            ->method('recupererPar')
            ->willReturnOnConsecutiveCalls($fakeNoeudCom, []);

        $this->service->requetePlusCourt("Agde", "NinjaSQL");
    }

    public function testRequetePlusCourtPtArriveeEtPtDepartNonRepertorie(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("départ ou arrivée inconnue");
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $fakeNoeudCom = [new NoeudCommune(0,0, "Test1", 0 )];

        $this->noeudCommuneRepositoryMock->expects($this->exactly(2))
            ->method('recupererPar')
            ->willReturnOnConsecutiveCalls($fakeNoeudCom, [null]);

        $this->service->requetePlusCourt("Agde", "NinjaSQL");
    }

    public function testRequetePlusCourtDernierNoeudNull(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Le trajet est impossible");
        $this->expectExceptionCode(400);

        $fakeNoeudCom = [new NoeudCommune(0,0, "Test1", 0 )];
        $this->noeudCommuneRepositoryMock->method("recupererPar")->willReturn($fakeNoeudCom);
        $fakeNoeudRout = [new NoeudRoutier(1,"2",null, $this->noeudRoutierRepositoryMock)];
        $this->noeudRoutierRepositoryMock->method("recupererPar")->willReturn($fakeNoeudRout);

        $this->plusCourtCheminMock->method("calculer3")->willReturn(null);

        $this->service->requetePlusCourt("Test1", "Montpellier");
    }

//    public function testRequetePlusCourtPass(){
//        $fakeNoeudCom = [new NoeudCommune(0,0, "Test1", 0 )];
//        $this->noeudCommuneRepositoryMock->method("recupererPar")->willReturn($fakeNoeudCom);
//        $fakeNoeudRout = [new NoeudRoutier(1,"2",null, $this->noeudRoutierRepositoryMock)];
//        $this->noeudRoutierRepositoryMock->method("recupererPar")->willReturn($fakeNoeudRout);
//
//        $fakeNoeudStar = new NoeudStar("Test",['yo','yo','yo'],2.20);
//        $plucourt = new PlusCourtChemin(1,1,$this->noeudRoutierRepositoryMock);
//        $plucourt->calculer3();
//        $this->plusCourtCheminMock->method("calculer3")->willReturn($fakeNoeudStar);
//
//        $this->assertEquals(4, sizeof($this->service->requetePlusCourt("IUTCity", "Montpellier")));
//
//
//    }


    public function testCheminPlusCourtNCentryNULL(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Veuillez renseigner un point de départ et un point d'arrivée");
        $this->expectExceptionCode(400);

        $this->service->plusCourtCheminNC(null, "Montpellier");
        $this->service->plusCourtCheminNC("Montpellier",null);
        $this->service->plusCourtCheminNC(null,null);
    }

    public function testCheminPlusCourtNCDepartOuArriveeNonRepertories(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Veuillez renseigner un point de départ et un point d'arrivée valide");
        $this->expectExceptionCode(400);

        $this->noeudCommuneRepositoryMock->recupererPar([]);

        $this->service->plusCourtCheminNC("IUTCity", "Montpellier");
    }

    public function testCheminPlusCourtNCTotalTest(){

        $fakeNoeudCom = [new NoeudCommune(0,0, "Test1", 0 )];
        $this->noeudCommuneRepositoryMock->method("recupererPar")->willReturn($fakeNoeudCom);
        $fakeNoeudRout = [new NoeudRoutier(1,"2",null, $this->noeudRoutierRepositoryMock)];
        $this->noeudRoutierRepositoryMock->method("recupererPar")->willReturn($fakeNoeudRout);

        $this->assertEquals(3, sizeof($this->service->plusCourtCheminNC("IUTCity", "Montpellier")));
    }

    public function testGetNearNull(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Commune introuvable");
        $this->expectExceptionCode(400);

        $fakeNoeudCom = [new NoeudCommune(0,0, "Test1", 0 )];
        $this->noeudCommuneRepositoryMock->method("recupererPar")->willReturn($fakeNoeudCom);

        $this->service->getNearCoord(1, 1);
    }
    public function testGetNearPass(){
        $this->noeudCommuneRepositoryMock->method("recupererParProximite")->willReturn("Montpellier");
        $this->assertNotEmpty($this->service->getNearCoord(1, 1));
    }


}