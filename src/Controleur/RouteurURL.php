<?php
namespace App\PlusCourtChemin\Controleur;

require '../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
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
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurNoeudCommune::plusCourtChemin",
        ]);
        $routes->add("plusCourt", $route);



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


//
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

        $route = new Route("/{depart}/{arrivee}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurNoeudCommune::plusCourtChemin",
        ]);
        $routes->add("plusCourtChemin", $route);


        $route = new Route("/getPlusCourt/{depart}/{arrivee}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurNoeudCommune::requetePlusCourt"
        ]);
        $routes->add("requetePlusCourt", $route);






        $contexteRequete = (new RequestContext())->fromRequest($requete);
        //print_r($contexteRequete);

        $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);
        $generateurUrl = new UrlGenerator($routes, $contexteRequete);
        Conteneur::ajouterService("assistant",$assistantUrl);
        Conteneur::ajouterService("generateur",$generateurUrl);

        try {
            $associateurUrl = new UrlMatcher($routes, $contexteRequete);
            $donneesRoute = $associateurUrl->match($requete->getPathInfo());
            $requete->attributes->add($donneesRoute);

            $resolveurDeControleur = new ControllerResolver();
            $controleur = $resolveurDeControleur->getController($requete);

            $resolveurDArguments = new ArgumentResolver();
            $arguments = $resolveurDArguments->getArguments($requete, $controleur);

            $reponse = call_user_func_array($controleur, $arguments);
        }
        catch (ResourceNotFoundException $exception) {
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(), 404);
        }
        catch (MethodNotAllowedException $exception) {
            // Remplacez xxx par le bon code d'erreur
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage(), 405);
        }
        catch (\Exception $exception) {
            $reponse = ControleurGenerique::afficherErreur($exception->getMessage()) ;
        }
        $reponse->send();

    }




}