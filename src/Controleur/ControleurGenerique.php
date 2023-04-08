<?php

namespace Explore\Controleur;

use Explore\Lib\Conteneur;
use Explore\Lib\MessageFlash;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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

    public static function rediriger($route, $parametres=[]) : RedirectResponse
    {
        $gen = Conteneur::recupererService("generateur");
        $url = $gen->generate($route, $parametres);
        return new RedirectResponse($url);

    }

    public static function afficherErreur($errorMessage = "", $statusCode = 400): Response
    {
        try {
            $reponse = ControleurGenerique::afficherVue('vueGenerale.php', [
                "pagetitle" => "Problème",
                "cheminVueBody" => "erreur.php",
                "errorMessage" => $errorMessage
            ]);
            // 3 méthodes qui lèvent des exceptions
        } catch (MethodNotAllowedException $exception) {
            // Remplacez xxx par le bon code d'erreur
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(), 405);
        } catch (LogicException $exception) {
            // Remplacez xxx par le bon code d'erreur
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(), 400);
        } catch (ResourceNotFoundException $exception) {
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(),404) ;
        }

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