<?php

namespace Explore\Lib;

use Explore\Configuration\ConfigurationBDDPostgreSQL;
use Explore\Modele\DataObject\Utilisateur;
use Explore\Modele\HTTP\Session;
use Explore\Modele\Repository\ConnexionBaseDeDonnees;
use Explore\Modele\Repository\UtilisateurRepository;
use Explore\Modele\Repository\UtilisateurRepositoryInterface;
use Explore\Service\UtilisateurService;
use Explore\Service\UtilisateurServiceInterface;

class ConnexionUtilisateur
{
    private static string $cleConnexion = "_utilisateurConnecte";


    private UtilisateurService $utilisateurService;


    public static function connecter(string $loginUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer(ConnexionUtilisateur::$cleConnexion, $loginUtilisateur);
    }

    public static function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->existeCle(ConnexionUtilisateur::$cleConnexion);
    }

    public static function deconnecter()
    {
        $session = Session::getInstance();
        $session->supprimer(ConnexionUtilisateur::$cleConnexion);
    }

    public static function getLoginUtilisateurConnecte(): ?string
    {
        $session = Session::getInstance();
        if ($session->existeCle(ConnexionUtilisateur::$cleConnexion)) {
            return $session->lire(ConnexionUtilisateur::$cleConnexion);
        } else
            return null;
    }

    public static function estUtilisateur($login): bool
    {
        return (ConnexionUtilisateur::estConnecte() &&
            ConnexionUtilisateur::getLoginUtilisateurConnecte() == $login
        );
    }

    public static function estAdministrateur(): bool
    {
        $loginConnecte = ConnexionUtilisateur::getLoginUtilisateurConnecte();

        // Si personne n'est connecté
        if ($loginConnecte === null)
            return false;



        /** @var Utilisateur $utilisateurConnecte */
        $config = new ConfigurationBDDPostgreSQL();
        $postgres = new ConnexionBaseDeDonnees($config);
        $utilisateurRepository = new UtilisateurRepository($postgres);
        $utilisateurService = new UtilisateurService($utilisateurRepository);

        $utilisateurConnecte = $utilisateurService->recupererUtilisateur($loginConnecte);
        return $utilisateurService->userEstAdmin($utilisateurConnecte);
    }

    public static function estValide(): bool
    {
        $loginConnecte = ConnexionUtilisateur::getLoginUtilisateurConnecte();

        // Si personne n'est connecté
        if ($loginConnecte === null)
            return false;

        /** @var Utilisateur $utilisateurConnecte */
        $config = new ConfigurationBDDPostgreSQL();
        $postgres = new ConnexionBaseDeDonnees($config);
        $utilisateurRepository = new UtilisateurRepository($postgres);
        $utilisateurService = new UtilisateurService($utilisateurRepository);

        $utilisateurConnecte = $utilisateurService->recupererUtilisateur($loginConnecte);
        return $utilisateurService->userEstValide($utilisateurConnecte);
    }
}
