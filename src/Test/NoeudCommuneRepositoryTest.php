<?php
///*
//namespace Explore\Test;
//
//use Explore\Configuration\ConfigurationBDDInterface;
//use Explore\Modele\Repository\ConnexionBaseDeDonneesInterface;
//use Explore\Modele\Repository\NoeudCommuneRepository;
//use Explore\Modele\Repository\NoeudCommuneRepositoryInterface;
//use PHPUnit\Framework\TestCase;
//
//class NoeudCommuneRepositoryTest extends TestCase
//{
//    private static NoeudCommuneRepositoryInterface  $noeudCommuneRepository;
//
//    private static ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees;
//    private ConfigurationBDDInterface $configurationBDD;
///*
//    public static function setUpBeforeClass(): void
//    {
//        parent::setUpBeforeClass();
//        self::$configurationBDD = new ConnexionBaseDeDonnees(self::createMock(ConfigurationBDDInterface::class));
//
//        self::$connexionBaseDeDonnees = ;
//        self::$noeudCommuneRepository = new NoeudCommuneRepository(self::$connexionBaseDeDonnees);
//    }*/
//
//    protected function setUp(): void
//    {
//        parent::setUp();
//        self::$connexionBaseDeDonnees->getPdo()->query("INSERT INTO
//                                                         utilisateurs (idUtilisateur, login, password, adresseMail, profilePictureName)
//                                                         VALUES (1, 'test', 'test', 'test@example.com', 'test.png')");
//        self::$connexionBaseDeDonnees->getPdo()->query("INSERT INTO
//                                                         utilisateurs (idUtilisateur, login, password, adresseMail, profilePictureName)
//                                                         VALUES (2, 'test2', 'test2', 'test2@example.com', 'test2.png')");
//    }
//
//    public function testSimpleCountGetAll() {
//        $this->assertCount(2, self::$utilisateurRepository->getAll());
//    }
//
//
//
//
///*namespace TheFeed\Test;
//
//    use PHPUnit\Framework\TestCase;
//    use TheFeed\Modele\Repository\ConnexionBaseDeDonnees;
//    use TheFeed\Modele\Repository\ConnexionBaseDeDonneesInterface;
//    use TheFeed\Modele\Repository\UtilisateurRepository;
//    use TheFeed\Modele\Repository\UtilisateurRepositoryInterface;
//
//class UtilisateurRepositoryTest extends TestCase
//{
//    private static UtilisateurRepositoryInterface  $utilisateurRepository;
//
//    private static ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees;
//
//    public static function setUpBeforeClass(): void
//    {
//        parent::setUpBeforeClass();
//        self::$connexionBaseDeDonnees = new ConnexionBaseDeDonnees(new ConfigurationBDDTestUnitaire());
//        self::$utilisateurRepository = new UtilisateurRepository(self::$connexionBaseDeDonnees);
//    }
//
//    protected function setUp(): void
//    {
//        parent::setUp();
//        self::$connexionBaseDeDonnees->getPdo()->query("INSERT INTO
//                                                         utilisateurs (idUtilisateur, login, password, adresseMail, profilePictureName)
//                                                         VALUES (1, 'test', 'test', 'test@example.com', 'test.png')");
//        self::$connexionBaseDeDonnees->getPdo()->query("INSERT INTO
//                                                         utilisateurs (idUtilisateur, login, password, adresseMail, profilePictureName)
//                                                         VALUES (2, 'test2', 'test2', 'test2@example.com', 'test2.png')");
//    }
//
//    public function testSimpleCountGetAll() {
//        $this->assertCount(2, self::$utilisateurRepository->getAll());
//    }
//
//    protected function tearDown(): void
//    {
//        parent::tearDown();
//        self::$connexionBaseDeDonnees->getPdo()->query("DELETE FROM utilisateurs");
//    }
//
//}*/
//}*/