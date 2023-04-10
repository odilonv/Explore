<?php

namespace Explore\Lib;

use Explore\Modele\DataObject\Utilisateur;
use Explore\Modele\HTTP\Session;
use Explore\Modele\Repository\UtilisateurRepository;
use Explore\Modele\Repository\UtilisateurRepositoryInterface;

class ConnexionUtilisateur
{
    private static string $cleConnexion = "_utilisateurConnecte";
    private UtilisateurRepositoryInterface $utilisateurRepository;

    public function __construct(UtilisateurRepositoryInterface $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }


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

    public function estAdministrateur(): bool
    {
        $loginConnecte = ConnexionUtilisateur::getLoginUtilisateurConnecte();

        // Si personne n'est connecté
        if ($loginConnecte === null)
            return false;


        /** @var Utilisateur $utilisateurConnecte */
        $utilisateurConnecte = $this->utilisateurRepository->recupererParClePrimaire($loginConnecte);

        return ($utilisateurConnecte !== null && $utilisateurConnecte->getEstAdmin());
    }
}
