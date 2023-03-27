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




<div class="contain">
    <form action="controleurFrontal.php?action=plusCourtChemin&controleur=noeudCommune" class="insideDivide" method="post">
        <label class="underlineTravel">
            <img src="../ressources/img/icons/circle-solid.svg" class="iconsLocationStart">
            <input placeholder="Une ville de départ ? Ex: Montpellier" name="nomCommuneDepart" type="text"></label>
        <div class="circles">
            <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition"><img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
            <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
        </div>
        <label class="underlineTravel">
            <img src="../ressources/img/icons/location-dot-solid.svg" class="iconsLocation">
            <input placeholder="Où allons-nous ?" type="text" name="nomCommuneArrivee" class="nomCommuneArrivee">
            <button type="submit" id='searchButton'value="Calculer"><img src="../ressources/img/icons/location-arrow-solid.svg"></button>
        </label>


        <?php if (!empty($_POST)) { ?>
            <p>
                Le plus court chemin entre <?= $nomCommuneDepart ?> et <?= $nomCommuneArrivee ?> mesure <?= $distance ?>km.
            </p>
        <?php } ?>
    </form>
</div>
<script defer type="text/javascript" src="../ressources/js/search.js"></script>
