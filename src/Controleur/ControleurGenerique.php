<?php

namespace Explore\Controleur;

use Explore\Lib\Conteneur;
use Explore\Lib\MessageFlash;

class ControleurGenerique {

    protected static function afficherVue(string $cheminVue, array $parametres = []): void
    {
        extract($parametres);
        $messagesFlash = MessageFlash::lireTousMessages();
        require __DIR__ . "/../vue/$cheminVue";
    }

    // https://stackoverflow.com/questions/768431/how-do-i-make-a-redirect-in-php
    public static function rediriger($route, $parametres=[]) : RedirectResponse
    {

        /*$queryString = [];
        if ($action != "") {
            $queryString[] = "action=" . rawurlencode($action);
        }
        if ($controleur != "") {
            $queryString[] = "controleur=" . rawurlencode($controleur);
        }
        foreach ($query as $name => $value) {
            $name = rawurldecode($name);
            $value = rawurldecode($value);
            $queryString[] = "$name=$value";
        }
        $url = "Location: ./controleurFrontal.php?" . join("&", $queryString);
        */

        $generateur = Conteneur::recupererService("generateur");
        $url = "Location: ".$generateur->generate($route,$parametres);
        header($url);
        exit();
    }

    public static function afficherErreur($errorMessage = "", $controleur = ""): void
    {
        $errorMessageView = "Problème";
        if ($controleur !== "")
            $errorMessageView .= " avec le contrôleur $controleur";
        if ($errorMessage !== "")
            $errorMessageView .= " : $errorMessage";

        ControleurGenerique::afficherVue('vueGenerale.php', [
            "pagetitle" => "Problème",
            "cheminVueBody" => "erreur.php",
            "errorMessage" => $errorMessageView
        ]);
    }

}