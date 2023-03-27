<?php

use App\PlusCourtChemin\Controleur\RouteurQueryString;
use App\PlusCourtChemin\Lib\Psr4AutoloaderClass;

require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

// instantiate the loader
$loader = new Psr4AutoloaderClass();
// register the base directories for the namespace prefix
$loader->addNamespace('App\PlusCourtChemin', __DIR__ . '/../src');
// register the autoloader
$loader->register();

// Syntaxe alternative
// The null coalescing operator returns its first operand if it exists and is not null


\App\PlusCourtChemin\Controleur\RouteurURL::traiterRequete();

