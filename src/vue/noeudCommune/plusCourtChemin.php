<?php
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Generator\UrlGenerator;
/** @var UrlGenerator $generateur */
/** @var UrlHelper $assistant */
?>

<div class="contain" id="recherche">
    
    <!-- regler le lien du form pour que ça prenne en parametre les inputs-->
    <form autocomplete="off" id="form" action="./" class="insideDivide" method="post" onkeydown="return event.key != 'Enter';">
    <div id="form"  class="insideDivide">
        <label class="underlineTravel">
            <img src="<?=$assistant->getAbsoluteUrl("ressources/img/icons/circle-solid.svg")?>" class="iconsLocationStart">
            <div class="autocompletion" id="autocompletion1"></div>
            <input id="ville1" placeholder="Une ville de départ ? Ex: Montpellier" name="nomCommuneDepart" type="text"></label>
        <div class="circles">
            <img src="<?=$assistant->getAbsoluteUrl("ressources/img/icons/circle-solid.svg")?>" class="circleTransition">
            <img src="<?=$assistant->getAbsoluteUrl("ressources/img/icons/circle-solid.svg")?>" class="circleTransition">
            <img src="<?=$assistant->getAbsoluteUrl("ressources/img/icons/circle-solid.svg")?>" class="circleTransition">
        </div>
        <label class="underlineTravel" id="lineTravel2">
            <img src="<?=$assistant->getAbsoluteUrl("ressources/img/icons/location-dot-solid.svg")?>" class="iconsLocation">
            <div class="autocompletion" id="autocompletion2"></div>
            <input id="ville2" placeholder="Où allons-nous ?" type="text" name="nomCommuneArrivee" class="nomCommuneArrivee">
            <button id='searchButton'>
            <img src="<?=$assistant->getAbsoluteUrl("ressources/img/icons/location-arrow-solid.svg")?>"></button>
        </label>


        <?php
        if (!empty($nomCommuneDepart) && !empty($nomCommuneArrivee)) {
            if($distance<0){
                echo "<p> Il n'existe pas de trajet entre $nomCommuneDepart et $nomCommuneArrivee </p>";
            }
            else{
                echo "<p>Le plus court chemin entre $nomCommuneDepart et $nomCommuneArrivee mesure $distance km.</p>";
            }
        }
        ?>
    </div>
</div>
<script defer type="text/javascript" src="<?=$assistant->getAbsoluteUrl("ressources/js/search.js")?>"></script>
<script defer type="text/javascript" src="<?=$assistant->getAbsoluteUrl("ressources/js/trajet.js")?>"></script>
<script defer type="text/javascript" src="<?=$assistant->getAbsoluteUrl("ressources/js/autocompletion.js")?>"></script>

