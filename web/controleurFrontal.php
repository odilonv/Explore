<?php

use Explore\Lib\Psr4AutoloaderClass;
use Symfony\Component\HttpFoundation\Request;
require '../vendor/autoload.php';

/*require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// instantiate the loader
$loader = new Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App\PlusCourtChemin', __DIR__ . '/../src');
// register the autoloader
$loader->register();

// Syntaxe alternative
// The null coalescing operator returns its first operand if it exists and is not null*/

$requete = Request::createFromGlobals();
Explore\Controleur\RouteurURL::traiterRequete($requete)->send();

