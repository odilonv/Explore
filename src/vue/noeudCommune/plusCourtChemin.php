<!--<form action="" method="post">
    <fieldset>
        <legend>Plus court chemin </legend>
        <script src="../ressources/JS/autocompletion.js" defer></script>
        <p class="InputAddOn">
            <label class="InputAddOn-item" for="nomCommuneDepart_id">Nom de la commune de départ</label>
            <input class="InputAddOn-field" type="text" value="" placeholder="Ex : Menton" name="nomCommuneDepart" id="nomCommuneDepart_id" required>
            <div id="autocompletion"></div>
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





<section id="search">
    <div class="contain">
            <form action="" class="insideDivide" method="post">
            <div class="underlineTravel"><img src="../ressources/img/icons/circle-solid.svg" class="iconsLocationStart"><input placeholder="Une ville de départ ? Ex: Montpellier" name="nomCommuneDepart" type="text"></div>
            <div class="circles">
                <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition"><img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
                <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
            </div>

                <div id="organisation">
                    <div id="ajouterEtape"><img src="../ressources/img/plus.png"></div>
                </div>

            <div class="underlineTravel">
                <img src="../ressources/img/icons/location-dot-solid.svg" class="iconsLocation">
                <input placeholder="Où allons-nous ?" type="text" name="nomCommuneArrivee">
                <button type="submit" value="Calculer"><img src="../ressources/img/icons/location-arrow-solid.svg" class="icons"></button>
            </div>


        <?php if (!empty($_POST)) { ?>
            <p>
                Le plus court chemin entre <?= $nomCommuneDepart ?> et <?= $nomCommuneArrivee ?> mesure <?= $distance ?>km.
            </p>
        <?php } ?>
            </form>

    </div>
    <img src="../ressources/img/3d-illustration-travel-location.png" class="imgGlobe">
</section>
