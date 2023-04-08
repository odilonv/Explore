<?php
namespace Explore\Controleur;


use Explore\Configuration\ConfigurationBDDPostgreSQL;
use Explore\Modele\Repository\AbstractRepository;
use Explore\Modele\Repository\ConnexionBaseDeDonnees;
use Explore\Modele\Repository\NoeudCommuneRepository;
use Explore\Modele\Repository\NoeudRoutierRepository;
use Explore\Modele\Repository\UtilisateurRepository;
use Explore\Service\NoeudCommuneService;
use Explore\Service\UtilisateurService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
use Explore\Lib\Conteneur;
use Explore\Controleur\ControleurUtilisateur;

class RouteurURL
{
    public static function traiterRequete(Request $request) : Response {

        $routes = new RouteCollection();

        $route = new Route("/", [
            "_controller" => "noeudcommune_controleur::plusCourtChemin",
        ]);
        $routes->add("plusCourt", $route);


        // Route afficherFormulaireConnexion
        $route = new Route("/connexion", [
            "_controller" => "utilisateur_controleur::afficherFormulaireConnexion",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireConnexion", $route);



        $route = new Route("/connexion", [
            "_controller" => "utilisateur_controleur::connecter",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["POST"]);
        $routes->add("connecter", $route);

        $route = new Route("/deconnexion", [
            "_controller" => "utilisateur_controleur::deconnecter",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $routes->add("deconnecter", $route);





        $route = new Route("/inscription", [
            "_controller" => "utilisateur_controleur::afficherFormulaireCreation",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherFormulaireCreation", $route);

        $route = new Route("/inscription", [
            "_controller" => "utilisateur_controleur::creerDepuisFormulaire",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["POST"]);
        $routes->add("creerDepuisFormulaire", $route);



        $route = new Route("/modification/{idUser}", [
            "_controller" => "utilisateur_controleur::afficherFormulaireMiseAJour",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $routes->add("afficherFormulaireMiseAJour", $route);

        $route = new Route("/modification/{idUser}", [
            "_controller" => "utilisateur_controleur::mettreAJour",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $routes->add("mettreAJour ", $route);


//
        $route = new Route("/utilisateurs", [
            "_controller" => "utilisateur_controleur::afficherListe",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $routes->add("afficherListe", $route);


        $route = new Route("/utilisateur/{idUser}", [
            "_controller" => "utilisateur_controleur::afficherDetail",
            // Syntaxes équivalentes
            // "_controller" => ControleurUtilisateur::class . "::afficherFormulaireConnexion",
            // "_controller" => [ControleurUtilisateur::class, "afficherFormulaireConnexion"],
        ]);
        $route->setMethods(["GET"]);
        $routes->add("afficherDetail", $route);

        $route = new Route("/noeudscommune", [
            "_controller" => "noeudcommune_controleur::afficherListe",

        ]);
        $routes->add("noeudscommune", $route);

        $route = new Route("/{depart}/{arrivee}", [
            "_controller" => "noeudcommune_controleur::plusCourtChemin",

        ]);
        $routes->add("plusCourtChemin", $route);
        */

        $route = new Route("/requeteVille/{ville}", [
            "_controller" => "\App\PlusCourtChemin\Controleur\ControleurNoeudCommune::requeteVille",
        ]);
        $route->setMethods(["GET"]);
        $routes->add("requeteVille", $route);


        $route = new Route("/getPlusCourt/{depart}/{arrivee}", [
            "_controller" => "noeudcommune_controleur::requetePlusCourt"
        ]);
        $routes->add("requetePlusCourt", $route);







        // $twigLoader = new FilesystemLoader(__DIR__ . '/../vue/');
        // $twig = new Environment(
        //     $twigLoader,
        //     [
        //         'autoescape' => 'html',
        //         'strict_variables' => true
        //     ]
        // );
        // Conteneur::ajouterService("twig", $twig);


        $contexteRequete = (new RequestContext())->fromRequest($request);
        //print_r($contexteRequete);

        $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);
        $generateurUrl = new UrlGenerator($routes, $contexteRequete);
        Conteneur::ajouterService("assistant",$assistantUrl);
        Conteneur::ajouterService("generateur",$generateurUrl);

        $conteneur = new ContainerBuilder();

        $conteneur->register('config_bdd', ConfigurationBDDPostgreSQL::class);

        $connexionBaseService = $conteneur->register('connexion_base', ConnexionBaseDeDonnees::class);
        $connexionBaseService->setArguments([new Reference('config_bdd')]);


        $utilisateurRepositoryService = $conteneur->register('utilisateur_repository',UtilisateurRepository::class);
        $utilisateurRepositoryService->setArguments([new Reference('connexion_base')]);

        $utilisateurService = $conteneur->register('utilisateur_service', UtilisateurService::class);
        $utilisateurService->setArguments([new Reference('utilisateur_repository')]);

        $utilisateurControleurService = $conteneur->register('utilisateur_controleur',ControleurUtilisateur::class);
        $utilisateurControleurService->setArguments([new Reference('utilisateur_service')]);


        $noeudRoutierRepositoryService = $conteneur->register('noeudroutier_repository',NoeudRoutierRepository::class);
        $noeudRoutierRepositoryService->setArguments([new Reference('connexion_base')]);


        $noeudCommuneRepositoryService = $conteneur->register('noeudcommune_repository',NoeudCommuneRepository::class);
        $noeudCommuneRepositoryService->setArguments([new Reference('connexion_base')]);

        $noeudCommuneService = $conteneur->register('noeudcommune_service', NoeudCommuneService::class);
        $noeudCommuneService->setArguments([new Reference('noeudcommune_repository'), new Reference('noeudroutier_repository')]);

        $noeudCommuneControleurService = $conteneur->register('noeudcommune_controleur',ControleurNoeudCommune::class);
        $noeudCommuneControleurService->setArguments([new Reference('noeudcommune_service')]);


        $associateurUrl = new UrlMatcher($routes, $contexteRequete);
        $donneesRoute = $associateurUrl->match($request->getPathInfo());

        /*
         * @throws NoConfigurationException  If no routing configuration could be found
         * @throws ResourceNotFoundException If the resource could not be found
         * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
         */


        //print_r($donneesRoute);

        $request->attributes->add($donneesRoute);

        $resolveurDeControleur = new ContainerControllerResolver($conteneur);
        $controleur = $resolveurDeControleur->getController($request);
        /*
         * @throws \LogicException If a controller was found based on the request but it is not callable
         */

        $resolveurDArguments = new ArgumentResolver();
        $arguments = $resolveurDArguments->getArguments($request, $controleur);
        /*
        *  @throws \RuntimeException When no value could be provided for a required argument
        */

        return call_user_func_array($controleur, $arguments);

    }




}