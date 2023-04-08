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



    public function creerDepuisFormulaire(): Response {

        try {
            //Enregistrer l'utilisateur via le service
            $login = $_POST['login'] ?? null;
            $password = $_POST['mdp'] ?? null;
            $adresseMail = $_POST['email'] ?? null;
            $profilePicture = $_FILES['profilePicture'] ?? null;



            $this->utilisateurService->creerUtilisateur($login,$password,$adresseMail,$profilePicture);

            MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");
            return ControleurNoeudCommune::rediriger("plusCourt");
        }
        catch(ServiceException $e) {
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurUtilisateur::rediriger("afficherFormulaireCreation");
        }

    }

    // FUSIONNER USER POUR RENDRE PROPRE

//    public static function creerDepuisFormulaire(): RedirectResponse
//    {
//        if (
//            isset($_REQUEST['login']) && isset($_REQUEST['prenom']) && isset($_REQUEST['nom'])
//            && isset($_REQUEST['mdp']) && isset($_REQUEST['mdp2'])
//        ) {
//            if ($_REQUEST["mdp"] !== $_REQUEST["mdp2"]) {
//                MessageFlash::ajouter("warning", "Mots de passe distincts.");
//                return ControleurUtilisateur::rediriger( "afficherFormulaireCreation");
//            }
//
//            /*if (!ConnexionUtilisateur::estAdministrateur()) {
//                unset($_REQUEST["estAdmin"]);
//            }*/
//
//            if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
//                MessageFlash::ajouter("warning", "Email non valide");
//                return ControleurUtilisateur::rediriger("afficherFormulaireCreation");
//            }
//
//            $utilisateur = Utilisateur::construireDepuisFormulaire($_REQUEST);
//
//            VerificationEmail::envoiEmailValidation($utilisateur);
//
//            $utilisateurRepository = new UtilisateurRepository();
//            $succesSauvegarde = $utilisateurRepository->ajouter($utilisateur);
//            if ($succesSauvegarde) {
//                MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");
//                return ControleurUtilisateur::rediriger( "afficherListe");
//            } else {
//                MessageFlash::ajouter("warning", "Login existant.");
//                return ControleurUtilisateur::rediriger( "afficherFormulaireCreation");
//            }
//        } else {
//            MessageFlash::ajouter("danger", "Login, nom, prenom ou mot de passe manquant.");
//            return ControleurUtilisateur::rediriger( "afficherFormulaireCreation");
//        }
//    }

    public static function afficherFormulaireConnexion(): Response
    {
        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
            "pagetitle" => "Formulaire de connexion",
            "cheminVueBody" => "utilisateur/formulaireConnexion.php",
            "method" => Configuration::getDebug() ? "get" : "post",
        ]);
    }

    public function connecter(): RedirectResponse
    {
        $login = $_POST['login'] ?? null;
        $password = $_POST['password'] ?? null;
        try {
            $this->utilisateurService->connecterUtilisateur($login, $password);
            MessageFlash::ajouter("success", "Connexion effectuée.");
            return ControleurUtilisateur::rediriger("connexion");
        }
        catch(ServiceException $e) {
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurUtilisateur::rediriger("afficherFormulaireCreation");
        }

    }

    public function deconnecter(): RedirectResponse
    {
        try {
            $this->utilisateurService->deconnecterUtilisateur();
            MessageFlash::ajouter("success", "L'utilisateur a bien été déconnecté.");
            return ControleurNoeudCommune::rediriger("plusCourt");
        }
        catch(ServiceException $e) {
            MessageFlash::ajouter('error', $e->getMessage());
            return ControleurNoeudCommune::rediriger("plusCourt");
        }
    }

    public function afficherListe() :  Response
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





    // REFAIRE :


//    public static function afficherDetail($login = null): Response
//    {
//        if ($login != null) {
//            $login = $_REQUEST['login'];
//            $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
//            if ($utilisateur === null) {
//                MessageFlash::ajouter("warning", "Login inconnu.");
//                return ControleurUtilisateur::rediriger("afficherListe");
//            } else {
//                return ControleurUtilisateur::afficherVue('vueGenerale.php', [
//                    "utilisateur" => $utilisateur,
//                    "pagetitle" => "Détail de l'utilisateur",
//                    "cheminVueBody" => "utilisateur/detail.php"
//                ]);
//            }
//        } else {
//            MessageFlash::ajouter("danger", "Login manquant.");
//            return ControleurUtilisateur::rediriger("afficherListe");
//        }
//    }
//
//    public static function supprimer(): RedirectResponse
//    {
//        if (isset($_REQUEST['login'])) {
//            $login = $_REQUEST['login'];
//            $utilisateurRepository = new UtilisateurRepository();
//            $deleteSuccessful = $utilisateurRepository->supprimer($login);
//            $utilisateurs = $utilisateurRepository->recuperer();
//            if ($deleteSuccessful) {
//                MessageFlash::ajouter("success", "L'utilisateur a bien été supprimé !");
//                return ControleurUtilisateur::rediriger("afficherListe");
//            } else {
//                MessageFlash::ajouter("warning", "Login inconnu.");
//                return ControleurUtilisateur::rediriger("afficherListe");
//            }
//        } else {
//            MessageFlash::ajouter("danger", "Login manquant.");
//            return ControleurUtilisateur::rediriger("afficherListe");
//        }
//    }
//

//    public static function creerDepuisFormulaire(): RedirectResponse
//    {
//        if (
//            isset($_REQUEST['login']) && isset($_REQUEST['prenom']) && isset($_REQUEST['nom'])
//            && isset($_REQUEST['mdp']) && isset($_REQUEST['mdp2'])
//        ) {
//            if ($_REQUEST["mdp"] !== $_REQUEST["mdp2"]) {
//                MessageFlash::ajouter("warning", "Mots de passe distincts.");
//                return ControleurUtilisateur::rediriger( "afficherFormulaireCreation");
//            }
//
//            /*if (!ConnexionUtilisateur::estAdministrateur()) {
//                unset($_REQUEST["estAdmin"]);
//            }*/
//
//            if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
//                MessageFlash::ajouter("warning", "Email non valide");
//                return ControleurUtilisateur::rediriger("afficherFormulaireCreation");
//            }
//
//            $utilisateur = Utilisateur::construireDepuisFormulaire($_REQUEST);
//
//            VerificationEmail::envoiEmailValidation($utilisateur);
//
//            $utilisateurRepository = new UtilisateurRepository();
//            $succesSauvegarde = $utilisateurRepository->ajouter($utilisateur);
//            if ($succesSauvegarde) {
//                MessageFlash::ajouter("success", "L'utilisateur a bien été créé !");
//                return ControleurUtilisateur::rediriger( "afficherListe");
//            } else {
//                MessageFlash::ajouter("warning", "Login existant.");
//                return ControleurUtilisateur::rediriger( "afficherFormulaireCreation");
//            }
//        } else {
//            MessageFlash::ajouter("danger", "Login, nom, prenom ou mot de passe manquant.");
//            return ControleurUtilisateur::rediriger( "afficherFormulaireCreation");
//        }
//    }
//
//    public static function afficherFormulaireMiseAJour($login = null): Response
//    {
//        if ($login != null) {
//            $login = $_REQUEST['login'];
//            /** @var Utilisateur $utilisateur */
//            $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
//            if ($utilisateur === null) {
//                MessageFlash::ajouter("danger", "Login inconnu.");
//                return ControleurUtilisateur::rediriger("afficherListe");
//            }
//            if (!(ConnexionUtilisateur::estUtilisateur($login) || ConnexionUtilisateur::estAdministrateur())) {
//                MessageFlash::ajouter("danger", "La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
//                return ControleurUtilisateur::rediriger("afficherListe");
//            }
//
//            $loginHTML = htmlspecialchars($login);
//            $prenomHTML = htmlspecialchars($utilisateur->getPrenom());
//            $nomHTML = htmlspecialchars($utilisateur->getNom());
//            $emailHTML = htmlspecialchars($utilisateur->getEmail());
//            return ControleurUtilisateur::afficherVue('vueGenerale.php', [
//                "pagetitle" => "Mise à jour d'un utilisateur",
//                "cheminVueBody" => "utilisateur/formulaireMiseAJour.php",
//                "loginHTML" => $loginHTML,
//                "prenomHTML" => $prenomHTML,
//                "nomHTML" => $nomHTML,
//                "emailHTML" => $emailHTML,
//                "estAdmin" => $utilisateur->getEstAdmin(),
//                "method" => Configuration::getDebug() ? "get" : "post",
//            ]);
//        } else {
//            MessageFlash::ajouter("danger", "Login manquant.");
//            return ControleurUtilisateur::rediriger( "afficherListe");
//        }
//    }
//
//
//    // Ajouter tous les parametres dans la définition
//    public static function mettreAJour(): RedirectResponse
//    {
//        if (!(isset($_REQUEST['login']) && isset($_REQUEST['prenom']) && isset($_REQUEST['nom'])
//            && isset($_REQUEST['mdp']) && isset($_REQUEST['mdp2']) && isset($_REQUEST['mdpAncien'])
//            && isset($_REQUEST['email'])
//        )) {
//            MessageFlash::ajouter("danger", "Login, nom, prenom, email ou mot de passe manquant.");
//            return ControleurUtilisateur::rediriger( "afficherListe");
//        }
//
//        if ($_REQUEST["mdp"] !== $_REQUEST["mdp2"]) {
//            MessageFlash::ajouter("warning", "Mots de passe distincts.");
//            return ControleurUtilisateur::rediriger( "afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
//        }
//
//        if (!(ConnexionUtilisateur::estConnecte($_REQUEST["login"]) || ConnexionUtilisateur::estAdministrateur())) {
//            MessageFlash::ajouter("danger", "La mise à jour n'est possible que pour l'utilisateur connecté ou un administrateur");
//            return ControleurUtilisateur::rediriger("afficherListe");
//        }
//
//        if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)) {
//            MessageFlash::ajouter("warning", "Email non valide");
//            return ControleurUtilisateur::rediriger("afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
//        }
//
//        $utilisateurRepository = new UtilisateurRepository();
//        /** @var Utilisateur $utilisateur */
//        $utilisateur = $utilisateurRepository->recupererParClePrimaire($_REQUEST['login']);
//
//        if ($utilisateur == null) {
//            MessageFlash::ajouter("danger", "Login inconnu");
//            return ControleurUtilisateur::rediriger("afficherListe");
//        }
//
//        if (!MotDePasse::verifier($_REQUEST["mdpAncien"], $utilisateur->getMdpHache())) {
//            MessageFlash::ajouter("warning", "Ancien mot de passe erroné.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireMiseAJour", ["login" => $_REQUEST["login"]]);
//        }
//
//        $utilisateur->setNom($_REQUEST["nom"]);
//        $utilisateur->setPrenom($_REQUEST["prenom"]);
//        $utilisateur->setMdpHache($_REQUEST["mdp"]);
//
//        if (ConnexionUtilisateur::estAdministrateur()) {
//            $utilisateur->setEstAdmin(isset($_REQUEST["estAdmin"]));
//        }
//
//        if ($_REQUEST["email"] !== $utilisateur->getEmail()) {
//            $utilisateur->setEmailAValider($_REQUEST["email"]);
//            $utilisateur->setNonce(MotDePasse::genererChaineAleatoire());
//
//            VerificationEmail::envoiEmailValidation($utilisateur);
//        }
//
//        $utilisateurRepository->mettreAJour($utilisateur);
//
//        MessageFlash::ajouter("success", "L'utilisateur a bien été modifié !");
//        return ControleurUtilisateur::rediriger( "afficherListe");
//    }
//
//    public static function afficherFormulaireConnexion(): Response
//    {
//        return ControleurUtilisateur::afficherVue('vueGenerale.php', [
//            "pagetitle" => "Formulaire de connexion",
//            "cheminVueBody" => "utilisateur/formulaireConnexion.php",
//            "method" => Configuration::getDebug() ? "get" : "post",
//        ]);
//    }
//
//    public static function connecter(): RedirectResponse
//    {
//        if (!(isset($_REQUEST['login']) && isset($_REQUEST['mdp']))) {
//            MessageFlash::ajouter("danger", "Login ou mot de passe manquant.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireConnexion");
//        }
//        $utilisateurRepository = new UtilisateurRepository();
//        /** @var Utilisateur $utilisateur */
//        $utilisateur = $utilisateurRepository->recupererParClePrimaire($_REQUEST["login"]);
//
//        if ($utilisateur == null) {
//            MessageFlash::ajouter("warning", "Login inconnu.");
//            return ControleurUtilisateur::rediriger("afficherFormulaireConnexion");
//        }
//
//        if (!MotDePasse::verifier($_REQUEST["mdp"], $utilisateur->getMdpHache())) {
//            MessageFlash::ajouter("warning", "Mot de passe incorrect.");
//            return ControleurUtilisateur::rediriger( "afficherFormulaireConnexion");
//        }
//
//        if (!VerificationEmail::aValideEmail($utilisateur)) {
//            MessageFlash::ajouter("warning", "Adresse email non validée.");
//            return ControleurUtilisateur::rediriger( "afficherFormulaireConnexion");
//        }
//
//        ConnexionUtilisateur::connecter($utilisateur->getLogin());
//        MessageFlash::ajouter("success", "Connexion effectuée.");
//        return ControleurUtilisateur::rediriger( "afficherDetail", ["login" => $_REQUEST["login"]]);
//    }
//
//    public static function deconnecter(): RedirectResponse
//    {
//        if (!ConnexionUtilisateur::estConnecte()) {
//            MessageFlash::ajouter("danger", "Utilisateur non connecté.");
//            return ControleurUtilisateur::rediriger( "afficherListe");
//        }
//        ConnexionUtilisateur::deconnecter();
//        MessageFlash::ajouter("success", "L'utilisateur a bien été déconnecté.");
//        return ControleurUtilisateur::rediriger( "afficherListe");
//    }
//
//    public static function validerEmail(): RedirectResponse
//    {
//        if (isset($_REQUEST['login']) && isset($_REQUEST['nonce'])) {
//            $succesValidation = VerificationEmail::traiterEmailValidation($_REQUEST["login"], $_REQUEST["nonce"]);
//
//            if (!$succesValidation) {
//                MessageFlash::ajouter("warning", "Email de validation incorrect.");
//                return ControleurUtilisateur::rediriger( "afficherListe");
//            }
//
//            $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST["login"]);
//            MessageFlash::ajouter("warning", "Validation d'email réussie");
//            return ControleurUtilisateur::rediriger( "afficherDetail", ["login" => $_REQUEST["login"]]);
//        } else {
//            MessageFlash::ajouter("danger", "Login ou nonce manquant.");
//            return ControleurUtilisateur::rediriger( "afficherListe");
//        }
//    }


}
