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
    <link rel="shortcut icon" type="image/png" href="<?=$assistant->getAbsoluteUrl("ressources/img/3d-illustration-travel-location.png")?>"/>

    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>


    <link rel="stylesheet" href="<?=$assistant->getAbsoluteUrl("ressources/css/main.css")?>">
</head>
<body>

<div id="mapContainer"></div>
<div id="logo">
    <h1>Explore</h1>
    <img id="iconmenu" src="<?=$assistant->getAbsoluteUrl("ressources/img/icons/caret-down-solid.svg")?>">
</div>
<?php
if (!ConnexionUtilisateur::estConnecte()) {
    echo '
                    
                    <ul id="sousmenu" class="sousmenu-hidden">
                        <li><img src="' . $assistant->getAbsoluteUrl("ressources/img/icons/clock-solid.svg") . '" class="icons">
                        <h3>Historique</h3> </li>
                    </ul>
                    
                    ';
} else if(ConnexionUtilisateur::estConnecte()){
    echo '
                    
                    <ul id="sousmenu" class="sousmenu-hidden">
                        <li><img src="' . $assistant->getAbsoluteUrl("ressources/img/icons/clock-solid.svg") . '" class="icons">
                        <h3>Historique</h3> </li>
                    </ul>
                    
                    ';
}
?>


    <?php
            if(Utils::$debug){
                foreach (Utils::getLogs() as $log){
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

        foreach (["success", "info", "warning", "danger"] as $type) {
            foreach ($messagesFlash[$type] as $messageFlash) {
                echo <<<HTML
                            <div class="notification-hidden" id="notif">
                            <div class="messageFlash alert-$type">
                                <h3>Message d'Explorateur</h3>
                                <div class="ligne"></div>
                                <p></p>
                            </div>
                            </div>
                            HTML;
            }
        }
        ?>

        <?php

    if (!ConnexionUtilisateur::estConnecte()) {
        echo '
                    <div class="connectFooter clickable">
                    <div class="connectHeader">
                        <img src="' . $assistant->getAbsoluteUrl("ressources/img/icons/user-solid.svg") . '" class="icons">
                        <h2 id="h2Connexion">Se connecter</h2>
                      </div>
                    </div>
                    ';
    } else {
        $loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        echo '
                                <form class="connectFooter clickable">
                                    <!--<a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL" id="connectFooter">¡-->
                                    <a href="/utilisateur/{idUser}" id="connectFooter">
                                        <img src="' . $assistant->getAbsoluteUrl("ressources/img/icons/user-solid.svg") . '" class="icons">
                                        <h2>' .$loginHTML .'</h2>
                                    </a>
                                </form>
                    ';
    }
    ?>


<script defer type="text/javascript" src="<?=$assistant->getAbsoluteUrl("ressources/js/connect.js")?>"></script>
<script defer type="text/javascript" src="<?=$assistant->getAbsoluteUrl("ressources/js/menu.js")?>"></script>
<script defer type="text/javascript" src="<?=$assistant->getAbsoluteUrl("ressources/js/map.js")?>"></script>
<script defer type="text/javascript" src="<?=$assistant->getAbsoluteUrl("ressources/js/notifications.js")?>"></script>



</body>
</html>