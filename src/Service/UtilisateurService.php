<?php

namespace Explore\Service;

use Explore\Lib\ConnexionUtilisateur;
use Explore\Lib\MotDePasse;
use Explore\Modele\DataObject\Utilisateur;
use Explore\Modele\Repository\UtilisateurRepository;
use Explore\Modele\Repository\UtilisateurRepositoryInterface;
use Explore\Service\Exception\ServiceException;

class UtilisateurService implements UtilisateurServiceInterface
{
    private UtilisateurRepositoryInterface $utilisateurRepository;


    public function __construct(UtilisateurRepositoryInterface $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }
    /**
     * @throws ServiceException
     */
    public function creerUtilisateur($login, $password, $adresseMail, $profilePictureData): void
    {
        if (strlen($login) < 4 || strlen($login) > 20) {
            throw new ServiceException("Le login doit être compris entre 4 et 20 caractères!");
        }
        if (!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$#", $password)) {
            throw new ServiceException("Mot de passe invalide!");
        }
        if (!filter_var($adresseMail, FILTER_VALIDATE_EMAIL)) {
            throw new ServiceException("L'adresse mail est incorrecte!");
        }
        $utilisateurRepository = $this->utilisateurRepository;
        $utilisateur = $utilisateurRepository->getByLogin($login);
        if ($utilisateur != null) {
            throw new ServiceException("Ce login est déjà pris!");
        }

        $utilisateur = $utilisateurRepository->getByAdresseMail($adresseMail);
        if ($utilisateur != null) {
            throw new ServiceException("Un compte est déjà enregistré avec cette adresse mail!");
        }

        $passwordChiffre = MotDePasse::hacher($password);

        // Upload des photos de profil
        // Plus d'informations :
        // http://romainlebreton.github.io/R3.01-DeveloppementWeb/assets/tut4-complement.html

        // On récupère l'extension du fichier
        $explosion = explode('.', $profilePictureData['name']);
        $fileExtension = end($explosion);
        if (!in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
            throw new ServiceException("La photo de profil n'est pas au bon format!");
        }
        // La photo de profil sera enregistrée avec un nom de fichier aléatoire
        $pictureName = uniqid() . '.' . $fileExtension;
        $from = $profilePictureData['tmp_name'];
        $to = __DIR__ . "/../../web/assets/img/utilisateurs/$pictureName";
        move_uploaded_file($from, $to);

        $utilisateur = Utilisateur::construireDepuisFormulaire($login, $passwordChiffre, $adresseMail, $pictureName);
        $utilisateurRepository->ajouter($utilisateur);

    }

    /**
     * @throws ServiceException
     */
    public function recupererUtilisateur($idUtilisateur, $autoriserNull = true) {
        $utilisateur = $this->utilisateurRepository->recupererParClePrimaire($idUtilisateur);
        if(!$autoriserNull && $utilisateur!=null) {
            throw new ServiceException('L\'utilisateur n\'existe pas !');
        }
        return $utilisateur;
    }

    /**
     * @throws ServiceException
     */
    public function recupererListeUtilisateur($autoriserNull = true)
    {
        $utilisateurs = $this->utilisateurRepository->recuperer();
        if(!$autoriserNull && $utilisateurs!=null) {
            throw new ServiceException('L\'utilisateur n\'existe pas !');
        }
        else{
            return $utilisateurs;
        }

    }

    /**
     * @throws ServiceException
     */
    public function connecterUtilisateur($login, $password): void
    {
        if ($login==null ||  $password==null) {
            throw new ServiceException("Login ou mot de passe manquant.");
        }
        $utilisateurRepository = $this->utilisateurRepository;
        /** @var Utilisateur $utilisateur */
        $utilisateur = $utilisateurRepository->getByLogin($_POST["login"]);

        if ($utilisateur == null) {
            throw new ServiceException("afficherFormulaireConnexion");
        }

        if (!MotDePasse::verifier($_POST["password"], $utilisateur->getMdpHache())) {
            throw new ServiceException("afficherFormulaireConnexion");
        }
        ConnexionUtilisateur::connecter($utilisateur->getLogin());
    }

    /**
     * @throws ServiceException
     */
    public function deconnecterUtilisateur(): void
    {
        if (!ConnexionUtilisateur::estConnecte()) {
            throw new ServiceException("Utilisateur non connecté.");
        }
        ConnexionUtilisateur::deconnecter();
    }

}