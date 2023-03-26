<?php

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;
use App\PlusCourtChemin\Lib\Conteneur;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Generator\UrlGenerator;

/** @var UrlGenerator $generateurUrl */
$generateurUrl = Conteneur::recupererService("generateur");
/** @var UrlHelper $assistantUrl */
$assistantUrl = Conteneur::recupererService("assistant");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <title><?= $pagetitle ?></title>
    <link rel="shortcut icon" type="image/png" href="../ressources/img/3d-illustration-travel-location.png"/>

    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>


    <link rel="stylesheet" href="../ressources/css/main.css">
</head>
<body>


<div id="mapContainer"></div>
<div id="logo">
    <h1>Explore</h1>
</div>
    <div>
        <?php
                    foreach (["success", "info", "warning", "danger"] as $type) {
                        foreach ($messagesFlash[$type] as $messageFlash) {
                            echo <<<HTML
                            <div class="alert alert-$type">
                                $messageFlash
                            </div>
                            HTML;
                        }
                    }
                    ?>
    </div>
    <?php
            /**
             * @var string $cheminVueBody
             */
    require __DIR__ . "/{$cheminVueBody}";
    ?>


    <footer>
        <?php


        if (!ConnexionUtilisateur::estConnecte()) {
            echo <<<HTML
                    <div class="connectFooter clickable">
                    <div class="connectHeader">
                        <img src="../ressources/img/icons/user-solid.svg" class="icons">
                        <h2 id="h2Connexion">Se connecter</h2>
                      </div>
                    </div>
                    HTML;
        } else {
            $loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            echo <<<HTML
                                <form class="connectFooter clickable">
                                    <a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL" id="connectFooter">
                                        <img src="../ressources/img/icons/user-solid.svg" class="icons">
                                        <h2>$loginHTML</h2>
                                    </a>
                                </form>
                    HTML;
        }
        ?>
        <!--<div id="echelleFooter">
            <a href=""><img src="../ressources/img/icons/minus-solid.svg" class="icons"></a>
            <a href=""><img src="../ressources/img/icons/plus-solid.svg" class="icons"></a>
        </div>
        <a id="signFooter" href="">
            <h2>Explore</h2>
        </a>-->
    </footer>



<script defer type="text/javascript" src="../ressources/js/connect.js"></script>
<script defer type="text/javascript" src="../ressources/js/map.js"></script>
<script defer type="text/javascript" src="../ressources/js/search.js"></script>

</body>
</html>