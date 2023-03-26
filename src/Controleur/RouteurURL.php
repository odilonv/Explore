<?php
namespace App\PlusCourtChemin\Controleur;

require '../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use App\PlusCourtChemin\Lib\Conteneur;
use App\PlusCourtChemin\Controleur\ControleurUtilisateur;

class RouteurURL
{
    public static function traiterRequete() {
        $requete = Request::createFromGlobals();
        $routes = new RouteCollection();

        // Route feed
        $route = new Route("/", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::accueil",
        ]);
        $routes->add("accueil", $route);



        // Route afficherFormulaireConnexion
        $route = new Route("/connexion", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherFormulaireConnexion",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireConnexion", $route);



        $route = new Route("/connexion", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::connecter",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["POST"]);
        $routes->add("connecter", $route);

        $route = new Route("/deconnexion", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::deconnecter",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $routes->add("deconnecter", $route);





        $route = new Route("/inscription", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherFormulaireCreation",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireCreation", $route);

        $route = new Route("/inscription", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::creerDepuisFormulaire",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["POST"]);
        $routes->add("creerDepuisFormulaire", $route);



        $route = new Route("/modification/{idUser}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherFormulaireMiseAJour",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $routes->add("afficherFormulaireMiseAJour", $route);

        $route = new Route("/modification/{idUser}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::mettreAJour",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $routes->add("mettreAJour ", $route);



        $route = new Route("/utilisateurs", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherListe",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $routes->add("afficherListe", $route);


        $route = new Route("/utilisateur/{idUser}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurUtilisateur::afficherDetail",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherDetail", $route);






        $contexteRequete = (new RequestContext())->fromRequest($requete);
        //print_r($contexteRequete);

        $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);
        $generateurUrl = new UrlGenerator($routes, $contexteRequete);
        Conteneur::ajouterService("assistant",$assistantUrl);
        Conteneur::ajouterService("generateur",$generateurUrl);


        $associateurUrl = new UrlMatcher($routes, $contexteRequete);
        $donneesRoute = $associateurUrl->match($requete->getPathInfo());
        /*
         * @throws NoConfigurationException  If no routing configuration could be found
         * @throws ResourceNotFoundException If the resource could not be found
         * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
         */


        //print_r($donneesRoute);

        $requete->attributes->add($donneesRoute);

        $resolveurDeControleur = new ControllerResolver();
        $controleur = $resolveurDeControleur->getController($requete);
        /*
         * @throws \LogicException If a controller was found based on the request but it is not callable
         */

        $resolveurDArguments = new ArgumentResolver();
        $arguments = $resolveurDArguments->getArguments($requete, $controleur);
        /*
        *  @throws \RuntimeException When no value could be provided for a required argument
        */

        $rep = call_user_func_array($controleur, $arguments);

    }




}