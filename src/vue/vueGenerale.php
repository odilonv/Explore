<?php
use Explore\Lib\ConnexionUtilisateur;
use Explore\Lib\Conteneur;
use Explore\Lib\Utils;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Generator\UrlGenerator;
/** @var UrlGenerator $generateur */
$generateur = Conteneur::recupererService("generateur");
/** @var UrlHelper $assistant */
$assistant = Conteneur::recupererService("assistant");
/** @var $pagetitle string */
/** @var $messagesFlash */
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <title><?= $pagetitle ?></title>
    <link rel="shortcut icon" type="image/png" href="<?=$assistant->getAbsoluteUrl( 'ressources/img/3d-illustration-travel-location.png' )?>" />

    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <script defer type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script defer type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script defer type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script defer type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
    <script defer type="module" src="<?= $assistant->getAbsoluteUrl('ressources/js/mapTools.js') ?>"></script>


    <link rel="stylesheet" href="<?= $assistant->getAbsoluteUrl("ressources/css/main.css") ?>">
</head>

<body>

    <div id="mapContainer" data-onclick="mapTools.mouseCoord()"></div>
    <div id="logo">
        <h1>Explore</h1>
        <img alt="iconmenu" id="iconmenu" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/caret-down-solid.svg") ?>">
    </div>

    <?php
    if (!ConnexionUtilisateur::estConnecte()) {
        echo '
                    <ul id="sousmenu" class="sousmenu-hidden">
                    <li>
                        <p>Connecte-toi pour accéder à plus d\'informations.</p> 
                        </li>
                    </ul>
                    
                    ';
    } else if (ConnexionUtilisateur::estConnecte() /*&& $admin*/) {
        echo
                       '<ul id="sousmenu" class="sousmenu-hidden">
                        <li><a href="'. $generateur->generate("noeudscommune") .'">
                            <img alt="boussole" src="' . $assistant->getAbsoluteUrl("ressources/img/icons/compass-solid.svg") . '" class="icons">
                            <h3>Communes</h3> 
                        </a></li>
                        <li><a href="'. $generateur->generate("afficherListe") .'">
                            <img alt="user" src="' . $assistant->getAbsoluteUrl("ressources/img/icons/user-solid.svg") . '" class="icons">
                            <h3>Utilisateurs</h3> 
                        </a></li>
                        
                    </ul>
                    ';
    }
    ?>


    <?php
    if (Utils::$debug) {
        foreach (Utils::getLogs() as $log) {
            echo $log . ' <br>';
        }
    }
    /**
     * @var string $cheminVueBody
     */
    require __DIR__ . "/{$cheminVueBody}";
    ?>



    <div id="loader"></div>
    <footer>
        <?php
        foreach (["success", "danger"] as $type) {
            foreach ($messagesFlash[$type] as $messageFlash) {
                echo ' 
                 <div class="notification-hidden" id="notif">
                        <div class="messageFlash alert-'.$type.'">
                            <div class="separate"><h3>Message de ton ami Explorateur</h3>
                            <img id="icon-exit-notif" src="' . $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid-white.svg") . '" alt="croix">
                            </div>
                            <div class="ligne"></div>
                            <div class="separate"><img alt="imgnotif" id="imgnotif" src="' . $assistant->getAbsoluteUrl("ressources/img/icons/$type-solid.svg") . '"><p>'.$messageFlash.'</p></div>
                        </div>
                 </div>';
            }
        }
        ?>



        <?php

        if (!ConnexionUtilisateur::estConnecte()) {
            echo '
                    <div class="connectFooter clickable">
                        <div class="connectHeader">
                            <img alt="profil" src="' . $assistant->getAbsoluteUrl("ressources/img/icons/user-solid.svg") . '" class="icons">
                            <h2>Se connecter</h2>
                        </div>
                    </div>
                    ';
        } else {
            $loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
            echo '
                                <div class="connectFooter clickable">
                                    <div class="connectHeader" id="connected">
                                        <a href="'.$generateur->generate("afficherDetail", ["loginUser" => ConnexionUtilisateur::getLoginUtilisateurConnecte()]).'" id="connectFooter">
                                        <img alt="detailUser" src="' . $assistant->getAbsoluteUrl("ressources/img/icons/user-solid.svg") . '" class="icons">
                                        </a>
                                        
                                        <a href="'.$generateur->generate("afficherDetail", ["loginUser" => ConnexionUtilisateur::getLoginUtilisateurConnecte()]).'" id="connectFooter">
                                            <h2>' . $loginHTML . '</h2>
                                        </a>
                                        
                                        <a href="'.$generateur->generate("deconnecter").'">
                                            <img alt="deconnecter" src="' . $assistant->getAbsoluteUrl("ressources/img/icons/arrow-right-from-bracket-solid.svg") . '" class="icons">
                                        </a>
                                    </div>
                                </div>
                    ';
        }
        ?>


        <script defer type="text/javascript" src="<?= $assistant->getAbsoluteUrl("ressources/js/connect.js") ?>"></script>
        <script defer type="text/javascript" src="<?= $assistant->getAbsoluteUrl("ressources/js/menu.js") ?>"></script>
        <script defer type="text/javascript" src="<?= $assistant->getAbsoluteUrl("ressources/js/map.js") ?>"></script>
        <script defer type="text/javascript" src="<?= $assistant->getAbsoluteUrl("ressources/js/notifications.js") ?>"></script>



</body>

</html>