<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Configuration\Configuration;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use App\PlusCourtChemin\Modele\Repository\UtilisateurRepository;

class VerificationEmail
{
    public static function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $loginURL = rawurlencode($utilisateur->getLogin());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $absoluteURL = Configuration::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=validerEmail&controleur=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";

        // Temporairement avant d'envoyer un vrai mail
        MessageFlash::ajouter("success", $corpsEmail);

        // mail(
        //     $utilisateur->getEmailAValider(),
        //     "Validation de votre adresse mail",
        //     "<a href=\"$lienValidationEmail\">Validation</a>"
        // );
    }

    public static function traiterEmailValidation($login, $nonce): bool
    {
        $utilisateurRepository = new UtilisateurRepository();
        /** @var Utilisateur $utilisateur */
        $utilisateur = $utilisateurRepository->recupererParClePrimaire($login);

        if ($utilisateur === null)
            return false;

        if ($utilisateur->getNonce() !== $nonce) {
            return false;
        }

        $utilisateur->setEmail($utilisateur->getEmailAValider());
        $utilisateur->setEmailAValider("");
        $utilisateur->setNonce("");

        $utilisateurRepository->mettreAJour($utilisateur);
        return true;
    }

    public static function aValideEmail(Utilisateur $utilisateur): bool
    {
        return $utilisateur->getEmail() !== "";
    }
}
