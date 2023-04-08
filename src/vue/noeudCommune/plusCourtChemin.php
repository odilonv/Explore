<!--<form action="" method="post">
    <fieldset>
        <legend>Plus court chemin </legend>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nomCommuneDepart_id">Nom de la commune de départ</label>
            <input class="InputAddOn-field" type="text" value="" placeholder="Ex : Menton" name="nomCommuneDepart" id="nomCommuneDepart_id" required>
        </p>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nomCommuneArrivee_id">Nom de la commune de départ</label>
            <input class="InputAddOn-field" type="text" value="" placeholder="Ex : Menton" name="nomCommuneArrivee" id="nomCommuneArrivee_id" required>
        </p>
        <input type="hidden" name="XDEBUG_TRIGGER">
        <p>
            <input class="InputAddOn-field" type="submit" value="Calculer" />
        </p>
    </fieldset>
</form>
-->
<?php /*if (!empty($_POST)) { */?><!--
    <p>
        Le plus court chemin entre <?/*= $nomCommuneDepart */?> et <?/*= $nomCommuneArrivee */?> mesure <?/*= $distance */?>km.
    </p>
--><?php /*} */?>




<div class="contain" id="recherche">
    
    <!-- regler le lien du form pour que ça prenne en parametre les inputs-->
    <form autocomplete="off" action="./" class="insideDivide" method="post">
        <label class="underlineTravel">
            <img src="../ressources/img/icons/circle-solid.svg" class="iconsLocationStart">
            <div class="autocompletion" id="autocompletion1"></div><input id="ville1" placeholder="Une ville de départ ? Ex: Montpellier" name="nomCommuneDepart" type="text"></label>
        <div class="circles">
            <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition"><img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
            <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
        </div>
        <label class="underlineTravel">
            <img src="../ressources/img/icons/location-dot-solid.svg" class="iconsLocation">
            <div class="autocompletion" id="autocompletion2"></div><input id="ville2" placeholder="Où allons-nous ?" type="text" name="nomCommuneArrivee" class="nomCommuneArrivee">
            <button type="submit" id='searchButton'value="Calculer"><img src="../ressources/img/icons/location-arrow-solid.svg"></button>
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
    </form>
</div>
<script defer type="text/javascript" src="../ressources/js/search.js"></script>
