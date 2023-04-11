<?php

namespace Explore\Controleur;

use Explore\Configuration\Configuration;
use Explore\Lib\ConnexionUtilisateur;
use Explore\Lib\MessageFlash;
use Explore\Lib\MotDePasse;
use Explore\Lib\VerificationEmail;
use Explore\Modele\DataObject\Utilisateur;
use Explore\Modele\Repository\UtilisateurRepository;
use Explore\Service\Exception\ServiceException;
use Explore\Service\UtilisateurServiceInterface;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\throwException;

class ControleurUtilisateur extends ControleurGenerique
{

    private UtilisateurServiceInterface $utilisateurService;

    public function __construct(UtilisateurServiceInterface $utilisateurService)
    {
        $this->utilisateurService = $utilisateurService;
    }

    public static function afficherErreur($errorMessage = "", $controleur = ""): Response
    {
        return parent::afficherErreur($errorMessage, "utilisateur");
    }



    public static function afficherFormulaireCreation(): Response
    {
        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "pagetitle" => "Création d'un utilisateur",
            "cheminVueBody" => "utilisateur/formulaireCreation.php",
            "method" => Configuration::getDebug() ? "get" : "post",
       ]);
    }


/*
    public static function afficherFormulaireCreation(): Response
    {
        return ControleurUtilisateur::afficherTwig('utilisateur/creation.html.twig', [
            "pagetitle" => "Création d'un utilisateur",
            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
    }
*/




    public function creerDepuisFormulaire(): Response
    {
        try {
            //Enregistrer l'utilisateur via le service
            $login = $_POST['login'] ?? null;
            $password = $_POST['mdp'] ?? null;
            $passwordVerif = $_POST['mdp2'] ?? null;
            $adresseMail = $_POST['email'] ?? null;
            $profilePicture = $_FILES['profilePicture'] ?? null;

            if($password != $passwordVerif)
            {
                throw new ServiceException("les mots de passe sont différents");
            }

            $this->utilisateurService->creerUtilisateur($login, $password, $adresseMail, $profilePicture);
            $user = $this->utilisateurService->recupererUtilisateur($login);

            MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");

            VerificationEmail::envoiEmailValidation($user);
            //rediriger vers mail de validation plutot
            return ControleurUtilisateur::rediriger("afficherFormulaireValidation");

        } catch (ServiceException $e) {
            MessageFlash::ajouter('danger', $e->getMessage());
            return ControleurUtilisateur::rediriger("afficherFormulaireCreation");
        }
    }

    public function afficherFormulaireValidation(): Response
    {
        $userConnecte = $this->utilisateurService->recupererUtilisateur(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        if(ConnexionUtilisateur::getLoginUtilisateurConnecte() == null || $this->utilisateurService->userEstValide($userConnecte))
        {
            return ControleurUtilisateur::rediriger("plusCourt");
        }
        else //l'user nest pas validé
        {
            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "pagetitle" => "Validation du compte",
                "cheminVueBody" => "utilisateur/formulaireValidation.php",
                "method" => Configuration::getDebug() ? "get" : "post",
            ]);
        }

    }

    public function renvoyerCode(): Response
    {
        $userConnecte = $this->utilisateurService->recupererUtilisateur(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        if(ConnexionUtilisateur::getLoginUtilisateurConnecte() == null || $this->utilisateurService->userEstValide($userConnecte))
        {
            return ControleurUtilisateur::rediriger("plusCourt");
        }
        else //l'user nest pas validé
        {
            VerificationEmail::envoiEmailValidation($userConnecte);
            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "pagetitle" => "Validation du compte",
                "cheminVueBody" => "utilisateur/formulaireValidation.php",
                "method" => Configuration::getDebug() ? "get" : "post",
            ]);
        }

    }

    public function validerUtilisateur():Response
    {
        $nonce = $_POST['nonce'] ?? null;
        $user = $this->utilisateurService->recupererUtilisateur(ConnexionUtilisateur::getLoginUtilisateurConnecte());

        if($nonce == null)
        {
            MessageFlash::ajouter('error', 'veuillez renseigner le code de validation');
            return ControleurUtilisateur::rediriger("afficherFormulaireValidation");
        }
        else
        {
            if($this->utilisateurService->verifierNonce($user,$nonce))
            {
                return ControleurUtilisateur::rediriger("plusCourt");
            }
            else
            {
                MessageFlash::ajouter('error', 'erreur lors de la validation');
                return ControleurUtilisateur::rediriger("afficherFormulaireValidation");
            }
        }

    }



    public function connecter(): RedirectResponse
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['mdp'] ?? null;
        try {
            $this->utilisateurService->connecterUtilisateur($login, $password);
            MessageFlash::ajouter("success", "Connexion effectuée.");
            return ControleurUtilisateur::rediriger("plusCourt");
        } catch (ServiceException $e) {
            MessageFlash::ajouter('danger', $e->getMessage());
            return ControleurUtilisateur::rediriger("plusCourt");
        }
    }

    public function deconnecter(): RedirectResponse
    {
        try {
            $this->utilisateurService->deconnecterUtilisateur();
            MessageFlash::ajouter("success", "L'utilisateur a bien été déconnecté.");
            return ControleurNoeudCommune::rediriger("plusCourt");
        } catch (ServiceException $e) {
            MessageFlash::ajouter('danger', $e->getMessage());
            return ControleurNoeudCommune::rediriger("plusCourt");
        }
    }

    public function afficherListe(): Response
    {
        try {
            $utilisateurs = $this->utilisateurService->recupererListeUtilisateur();
        } catch (ServiceException $e) {
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurNoeudCommune::rediriger("plusCourt");
        }
        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "utilisateurs" => $utilisateurs,
            "pagetitle" => "Liste des utilisateurs",
            "cheminVueBody" => "utilisateur/liste.php"
        ]);
    }

    public function afficherDetail($loginUser)
    {
        try {
            $loginUser = rawurldecode($loginUser);
            $user = $this->utilisateurService->recupererUtilisateur($loginUser,true);
            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $user,
                "pagetitle" => "Profil de ".$loginUser,
                "cheminVueBody" => "utilisateur/detail.php"
            ]);
        }
       catch (ServiceException $e)
        {
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurNoeudCommune::rediriger("utilisateurInconnu");
        }



    }

    public function utilisateurInconnu()
    {
        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "pagetitle" => "Utilisateur introuvable",
            "cheminVueBody" => "utilisateur/inconnu.php"
        ]);
    }

    public function historique(): Response
    {
        try {
            $historique = $this->utilisateurService->recupererHistorique(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "historique" => $historique,
                "pagetitle" => "Historique",
                "cheminVueBody" => "utilisateur/historique.php"
            ]);
        } catch (ServiceException $e) {
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurNoeudCommune::rediriger("plusCourt");
        }
    }

    public function mettreAJour(){
       try{
            $profilePicture = $_FILES['profilePicture'] ?? null;
            if($profilePicture == null)
            {
                throw new ServiceException("Veuillez renseigner un fichier");
            }
            $login = ConnexionUtilisateur::getLoginUtilisateurConnecte();
            if($login == null)
            {
                throw new ServiceException("Vous devez etre connecté");
            }
            $this->utilisateurService->update($login,$profilePicture);
            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" =>  $this->utilisateurService->recupererUtilisateur(ConnexionUtilisateur::getLoginUtilisateurConnecte()),
                "pagetitle" => "Profil de ".ConnexionUtilisateur::getLoginUtilisateurConnecte(),
                "cheminVueBody" => "utilisateur/detail.php"
            ]);
        }
        catch (ServiceException $e) {
            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $this->utilisateurService->recupererUtilisateur(ConnexionUtilisateur::getLoginUtilisateurConnecte()),
                "pagetitle" => "Profil de ".ConnexionUtilisateur::getLoginUtilisateurConnecte(),
                "cheminVueBody" => "utilisateur/detail.php"
            ]);
        }

    }

    public function supprimerUser($loginUser){
        try{
            $loginUser = rawurldecode($loginUser);
            if(!ConnexionUtilisateur::estAdministrateur() && ConnexionUtilisateur::getLoginUtilisateurConnecte() != $loginUser)
            {
                throw new ServiceException("Vous n'avez pas les droits pour");
            }

            $this->utilisateurService->delete($loginUser);

            if(ConnexionUtilisateur::getLoginUtilisateurConnecte() == $loginUser)
            {
                ConnexionUtilisateur::deconnecter();
            }

            return ControleurNoeudCommune::rediriger("plusCourt");
        }
        catch (ServiceException $e) {
            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "utilisateur" => $this->utilisateurService->recupererUtilisateur($loginUser),
                "pagetitle" => "Profil de ".ConnexionUtilisateur::getLoginUtilisateurConnecte(),
                "cheminVueBody" => "utilisateur/detail.php"
            ]);
        }
    }
}
