<?php

namespace App\PlusCourtChemin\Controleur;

use App\PlusCourtChemin\Lib\Conteneur;
use App\PlusCourtChemin\Lib\MessageFlash;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ControleurGenerique {

    protected static function afficherVue(string $cheminVue, array $parametres = []): Response
    {
        extract($parametres);
        $messagesFlash = MessageFlash::lireTousMessages();
        ob_start();
        require __DIR__ . "/../vue/$cheminVue";
        $corpsReponse = ob_get_clean();
        return new Response($corpsReponse);
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
        return new RedirectResponse($url);
        exit();

    }

    public static function afficherErreur($errorMessage = "", $statusCode = 400): Response
    {
        $reponse = ControleurGenerique::afficherVue('vueGenerale.php', [
            "pagetitle" => "Problème",
            "cheminVueBody" => "erreur.php",
            "errorMessage" => $errorMessage
        ]);

        $reponse->setStatusCode($statusCode);
        return $reponse;
    }

    protected static function afficherTwig(string $cheminVue, array $parametres = []): Response
    {
        /** @var Environment $twig */
        $twig = Conteneur::recupererService("twig");
        return new Response($twig->render($cheminVue, $parametres));
    }

}