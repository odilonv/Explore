<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Configuration\Configuration;
use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Lib\MessageFlash;
use App\PlusCourtChemin\Lib\MotDePasse;
use App\PlusCourtChemin\Lib\VerificationEmail;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;

class ControleurUtilisateur extends ControleurGenerique
{

    public static function afficherErreur($errorMessage = "", $controleur = ""): void
    {
        parent::afficherErreur($errorMessage, "utilisateur");
    }


    public static function afficherListe(): void
    {
        $utilisateurs = (new UtilisateurRepository())->recuperer();     //appel au modèle pour gerer la BD
        ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "utilisateurs" => $utilisateurs,
            "pagetitle" => "Liste des utilisateurs",
            "cheminVueBody" => "utilisateur/liste.php"
        ]);
    }

    public static function afficherDetail(): void
    {
        if (isset($_REQUEST['login'])) {
            $login = $_REQUEST['login'];
            $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
            if ($utilisateur === null) {
                MessageFlash::ajouter("warning", "Login inconnu.");
                ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
            } else {
                ControleurUtilisateur::afficherVue('vueGenerale.php', [
                    "utilisateur" => $utilisateur,
                    "pagetitle" => "Détail de l'utilisateur",
                    "cheminVueBody" => "utilisateur/detail.php"
                ]);
            }
        } else {
            MessageFlash::ajouter("danger", "Login manquant.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
        }
    }

    public static function supprimer()
    {
        if (isset($_REQUEST['login'])) {
            $login = $_REQUEST['login'];
            $utilisateurRepository = new UtilisateurRepository();
            $deleteSuccessful = $utilisateurRepository->supprimer($login);
            $utilisateurs = $utilisateurRepository->recuperer();
            if ($deleteSuccessful) {
                MessageFlash::ajouter("success", "L'utilisateur a bien été supprimé !");
                ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
            } else {
                MessageFlash::ajouter("warning", "Login inconnu.");
                ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
            }
        } else {
            MessageFlash::ajouter("danger", "Login manquant.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
        }
    }

    public static function afficherFormulaireCreation(): void
    {
        ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "pagetitle" => "Création d'un utilisateur",
            "cheminVueBody" => "utilisateur/formulaireCreation.php",
            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
    }

    public static function creerDepuisFormulaire(): void
    {
        if (
            isset($_REQUEST['login']) && isset($_REQUEST['prenom']) && isset($_REQUEST['nom'])
            && isset($_REQUEST['mdp']) && isset($_REQUEST['mdp2'])
        ) {
            if ($_REQUEST["mdp"] !== $_REQUEST["mdp2"]) {
                MessageFlash::ajouter("warning", "Mots de passe distincts.");
                ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireCreation");
            }

            if (!ConnexionUtilisateur::estAdministrateur()) {
                unset($_REQUEST["estAdmin"]);
            }

            if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
                MessageFlash::ajouter("warning", "Email non valide");
                ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireCreation");
            }

            $utilisateur = Utilisateur::construireDepuisFormulaire($_REQUEST);

            VerificationEmail::envoiEmailValidation($utilisateur);

            $utilisateurRepository = new UtilisateurRepository();
            $succesSauvegarde = $utilisateurRepository->ajouter($utilisateur);
            if ($succesSauvegarde) {
                MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");
                ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
            } else {
                MessageFlash::ajouter("warning", "Login existant.");
                ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireCreation");
            }
        } else {
            MessageFlash::ajouter("danger", "Login, nom, prenom ou mot de passe manquant.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireCreation");
        }
    }

    public static function afficherFormulaireMiseAJour(): void
    {
        if (isset($_REQUEST['login'])) {
            $login = $_REQUEST['login'];
            /** @var Utilisateur $utilisateur */
            $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
            if ($utilisateur === null) {
                MessageFlash::ajouter("danger", "Login inconnu.");
                ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
            }
            if (!(ConnexionUtilisateur::estUtilisateur($login) || ConnexionUtilisateur::estAdministrateur())) {
                MessageFlash::ajouter("danger", "La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
                ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
            }

            $loginHTML = htmlspecialchars($login);
            $prenomHTML = htmlspecialchars($utilisateur->getPrenom());
            $nomHTML = htmlspecialchars($utilisateur->getNom());
            $emailHTML = htmlspecialchars($utilisateur->getEmail());
            ControleurUtilisateur::afficherVue('vueGenerale.php', [
                "pagetitle" => "Mise à jour d'un utilisateur",
                "cheminVueBody" => "utilisateur/formulaireMiseAJour.php",
                "loginHTML" => $loginHTML,
                "prenomHTML" => $prenomHTML,
                "nomHTML" => $nomHTML,
                "emailHTML" => $emailHTML,
                "estAdmin" => $utilisateur->getEstAdmin(),
                "method" => Configuration::getDebug() ? "get" : "post",
            ]);
        } else {
            MessageFlash::ajouter("danger", "Login manquant.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
        }
    }

    public static function mettreAJour(): void
    {
        if (!(isset($_REQUEST['login']) && isset($_REQUEST['prenom']) && isset($_REQUEST['nom'])
            && isset($_REQUEST['mdp']) && isset($_REQUEST['mdp2']) && isset($_REQUEST['mdpAncien'])
            && isset($_REQUEST['email'])
        )) {
            MessageFlash::ajouter("danger", "Login, nom, prenom, email ou mot de passe manquant.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
        }

        if ($_REQUEST["mdp"] !== $_REQUEST["mdp2"]) {
            MessageFlash::ajouter("warning", "Mots de passe distincts.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
        }

        if (!(ConnexionUtilisateur::estConnecte($_REQUEST["login"]) || ConnexionUtilisateur::estAdministrateur())) {
            MessageFlash::ajouter("danger", "La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
        }

        if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
            MessageFlash::ajouter("warning", "Email non valide");
            ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
        }

        $utilisateurRepository = new UtilisateurRepository();
        /** @var Utilisateur $utilisateur */
        $utilisateur = $utilisateurRepository->recupererParClePrimaire($_REQUEST['login']);

        if ($utilisateur == null) {
            MessageFlash::ajouter("danger", "Login inconnu");
            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
        }

        if (!MotDePasse::verifier($_REQUEST["mdpAncien"], $utilisateur->getMdpHache())) {
            MessageFlash::ajouter("warning", "Ancien mot de passe erroné.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
        }

        $utilisateur->setNom($_REQUEST["nom"]);
        $utilisateur->setPrenom($_REQUEST["prenom"]);
        $utilisateur->setMdpHache($_REQUEST["mdp"]);

        if (ConnexionUtilisateur::estAdministrateur()) {
            $utilisateur->setEstAdmin(isset($_REQUEST["estAdmin"]));
        }

        if ($_REQUEST["email"] !== $utilisateur->getEmail()) {
            $utilisateur->setEmailAValider($_REQUEST["email"]);
            $utilisateur->setNonce(MotDePasse::genererChaineAleatoire());

            VerificationEmail::envoiEmailValidation($utilisateur);
        }

        $utilisateurRepository->mettreAJour($utilisateur);

        MessageFlash::ajouter("success", "L'utilisateur a bien été modifié !");
        ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
    }

    public static function afficherFormulaireConnexion(): void
    {
        ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "pagetitle" => "Formulaire de connexion",
            "cheminVueBody" => "utilisateur/formulaireConnexion.php",
            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
    }

    public static function connecter(): void
    {
        if (!(isset($_REQUEST['login']) && isset($_REQUEST['mdp']))) {
            MessageFlash::ajouter("danger", "Login ou mot de passe manquant.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireConnexion");
        }
        $utilisateurRepository = new UtilisateurRepository();
        /** @var Utilisateur $utilisateur */
        $utilisateur = $utilisateurRepository->recupererParClePrimaire($_REQUEST["login"]);

        if ($utilisateur == null) {
            MessageFlash::ajouter("warning", "Login inconnu.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireConnexion");
        }

        if (!MotDePasse::verifier($_REQUEST["mdp"], $utilisateur->getMdpHache())) {
            MessageFlash::ajouter("warning", "Mot de passe incorrect.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireConnexion");
        }

        if (!VerificationEmail::aValideEmail($utilisateur)) {
            MessageFlash::ajouter("warning", "Adresse email non validée.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherFormulaireConnexion");
        }

        ConnexionUtilisateur::connecter($utilisateur->getLogin());
        MessageFlash::ajouter("success", "Connexion effectuée.");
        ControleurUtilisateur::rediriger("utilisateur", "afficherDetail", ["login" => $_REQUEST["login"]]);
    }

    public static function deconnecter(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("danger", "Utilisateur non connecté.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
        }
        ConnexionUtilisateur::deconnecter();
        MessageFlash::ajouter("success", "L'utilisateur a bien été déconnecté.");
        ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
    }

    public static function validerEmail()
    {
        if (isset($_REQUEST['login']) && isset($_REQUEST['nonce'])) {
            $succesValidation = VerificationEmail::traiterEmailValidation($_REQUEST["login"], $_REQUEST["nonce"]);

            if (!$succesValidation) {
                MessageFlash::ajouter("warning", "Email de validation incorrect.");
                ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
            }

            $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST["login"]);
            MessageFlash::ajouter("warning", "Validation d'email réussie");
            ControleurUtilisateur::rediriger("utilisateur", "afficherDetail", ["login" => $_REQUEST["login"]]);
        } else {
            MessageFlash::ajouter("danger", "Login ou nonce manquant.");
            ControleurUtilisateur::rediriger("utilisateur", "afficherListe");
        }
    }


}
