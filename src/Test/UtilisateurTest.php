<?php

namespace Explore\Test;

use Explore\Modele\DataObject\Utilisateur;
use Explore\Modele\Repository\UtilisateurRepositoryInterface;
use Explore\Service\Exception\ServiceException;
use Explore\Service\UtilisateurService;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    private UtilisateurService $service;
    private UtilisateurRepositoryInterface $utilisateurRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->utilisateurRepositoryMock = $this->createMock(UtilisateurRepositoryInterface::class);
        $this->service = new UtilisateurService($this->utilisateurRepositoryMock);
    }

    /**
     * @throws ServiceException
     */
    public function testNombreUtilisateurs()
    {
        $fakeUsers = [new Utilisateur("test","mdphache", "Test1", 0 ),
            new Utilisateur("test","mdphache", "Test1", 0 )];
        $this->utilisateurRepositoryMock->method("recuperer")->willReturn($fakeUsers);
        $this->assertCount(2, $this->service->recupererListeUtilisateur());
    }

    public function testListeUtilisateursVide()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionCode(400);
        $this->utilisateurRepositoryMock->method("recuperer")->willReturn([]);
        $this->expectExceptionMessage('Aucun utilisateur n\'a été trouvé');
        $this->assertEmpty($this->service->recupererListeUtilisateur());
    }

    public function testgetUtilisateurInconnu(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('L\'utilisateur n\'existe pas !');
        $this->utilisateurRepositoryMock->method("recupererParClePrimaire")->willReturn(null);
        $this->service->recupererUtilisateur('lool');

    }

    public function testConnexionUtilisateurMdpManquant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Login ou mot de passe manquant.");
        $this->service->connecterUtilisateur('yoyo',null);
    }
    public function testConnexionUtilisateurUtilisateurInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Utilisateur inexistant");
        $this->utilisateurRepositoryMock->method('recupererParClePrimaire')->willReturn(null);
        $this->service->connecterUtilisateur('yoyo','test');
    }


    // TESTS CREATION UTILISATEUR

    public function testCreerTailleLoginInvalide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Le login doit être compris entre 4 et 20 caractères!');
        $this->service->creerUtilisateur('lll','','','');
    }

    public function testMotDePassePetitPasUneMajusculeEtUneMinuscule(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Mot de passe invalide!');
        $this->service->creerUtilisateur('llll','1234567','','');
    }
    public function testMailInvalide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('L\'adresse mail est incorrecte!');
        $this->service->creerUtilisateur('llll','1234567aB','okay','');
    }

    public function testLoginUtilisateurExisteDeja(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Ce login est déjà pris!');
        $fakeUser = new Utilisateur('Bonjour', '', '', '');
        $this->utilisateurRepositoryMock->method('recupererParClePrimaire')->willReturn($fakeUser);
        $this->service->creerUtilisateur('llll','1234567aB','okay@mail.com','');
    }

    public function testMailUtilisateurExisteDeja(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Un compte est déjà enregistré avec cette adresse mail!');
        $this->utilisateurRepositoryMock->method('recupererParClePrimaire')->willReturn(null);
        $this->utilisateurRepositoryMock->method('recupererPar')->willReturn(["email" => "okay@mail.com"]);
        $this->service->creerUtilisateur('llll','1234567aB','okay@mail.com','');
    }

    public function testPPMauvaisFormat(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('La photo de profil n\'est pas au bon format!');
        $this->utilisateurRepositoryMock->method('recupererParClePrimaire')->willReturn(null);
        $this->utilisateurRepositoryMock->method('recupererPar')->willReturn([]);

        $this->service->creerUtilisateur('llll','1234567aB','okay@mail.com',["name" => "yoyo.pdf"]);
    }

    public function testUtilisateurEnCreation(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('Un utilisateur est déjà en cours de validation pour ce login');
        $this->utilisateurRepositoryMock->method('recupererParClePrimaire')->willReturn(null);
        $this->utilisateurRepositoryMock->method('recupererPar')->willReturn([]);
        $this->utilisateurRepositoryMock->method("ajouterUserAValider")->willReturn(false);

        $this->service->creerUtilisateur('llll','1234567aB','okay@mail.com',null);
    }

    /*public function testUtilisateurPASS(){
        $this->utilisateurRepositoryMock->method('recupererParClePrimaire')->willReturn(null);
        $this->utilisateurRepositoryMock->method('recupererPar')->willReturn([]);
        $this->utilisateurRepositoryMock->method("ajouterUserAValider")->willReturn(true);
        $this->service->creerUtilisateur('llll','1234567aB','okay@mail.com',null);
    }*/


}