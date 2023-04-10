<?php

namespace Explore\Test;

use Explore\Modele\DataObject\Utilisateur;
use Explore\Modele\Repository\UtilisateurRepositoryInterface;
use Explore\Service\Exception\ServiceException;
use Explore\Service\UtilisateurService;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    private UtilisateurService $utilisateurService;
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

}