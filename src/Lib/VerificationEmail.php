<?php

namespace Explore\Lib;

use Explore\Configuration\Configuration;

use Explore\Configuration\ConfigurationBDDPostgreSQL;
use Explore\Modele\DataObject\Utilisateur;
use Explore\Modele\Repository\ConnexionBaseDeDonnees;
use Explore\Modele\Repository\UtilisateurRepository;
use Explore\Service\UtilisateurService;

class VerificationEmail
{
    public static function envoiEmailValidation(Utilisateur $utilisateur): void
    {
        $config = new ConfigurationBDDPostgreSQL();
        $postgres = new ConnexionBaseDeDonnees($config);
        $utilisateurRepository = new UtilisateurRepository($postgres);
        $utilisateurService = new UtilisateurService($utilisateurRepository);

        $subject = 'Validation de votre compte Explore';

        $headers = "From: Explore \r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message  = '


            <div align="center" style="display:block;height:100%; width:100%;background-color: whitesmoke; justify-content: center">
            <div style="text-align:center;height:100%; width:500px;background-color: white">
            <header style="background-color:#ffae9e;height:100px;">
            <div style="color: white; font-weight:bolder;font-size:26px;padding: 30px 0 0 0">Validez votre compte Explore !</div>
            </header>
            
            <div style="margin-top: 30px"> 
            <img alt="voter" src="https://media.discordapp.net/attachments/1050956357748150302/1095055073463242803/compass-solid.png?width=552&height=552"  width="100px" height="100px"></div>
            <p style="color: black;width:60%;margin-left:auto;margin-right:auto; font-weight:bold;font-size:18px"> Plus qu\'une étape!</p>
            <p style="color: #181818FF;width:60%;margin-left:auto;margin-right:auto;"> Utilisez le code de vérification ci-dessous pour finaliser votre inscription.</p>
            <div style="margin-left: 150px;margin-right: 150px;margin-bottom: 40px ;background:#ffffff;border:2px solid #e2e2e2;line-height:1.1;text-align:center;text-decoration:none;display:block;border-radius:8px;font-weight:bold;padding:10px 40px">
            <span style="color:#333;letter-spacing:5px">' . $utilisateurRepository->getNonce($utilisateur).'</span>
            </div>
            <div style="color:#7C7C7CFF;margin-top: 40px;margin-bottom: 20x;padding:10px 20px 10px 10px">Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet e-mail.</div>
            <footer style="background-color: black"><table role="presentation" width="100%" >
            <tbody><tr>
            <td style="padding:10px 10px 10px 20px" align="left"><div style="color: white;font-weight: bold"> exploresaewebsite@gmail.&#8203;com</div></td> 
            <td style="padding:10px 20px 10px 10px" align="right"><img alt="RichVote" src="https://cdn.discordapp.com/attachments/1050956357748150302/1095055520290848838/3d-illustration-travel-location.png" width="60px" height="60px">  </td> 
            </tr></tbody></table></footer>
            </div></div>
            <span style="opacity: 0">' . $utilisateur->getEmail().'</span>';

        mail($utilisateur->getEmail(),
            $subject,
            $message,
            $headers);
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
