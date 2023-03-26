

    <div class="contain">
        <form action="controleurFrontal.php?action=plusCourtChemin&controleur=noeudCommune" class="insideDivide" method="post">
            <div class="underlineTravel">
                <img src="../ressources/img/icons/circle-solid.svg" class="iconsLocationStart">
                <input placeholder="Une ville de départ ? Ex: Montpellier" name="nomCommuneDepart" type="text"></div>
            <div class="circles">
                <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition"><img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
                <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
            </div>
            <div class="underlineTravel">
                <img src="../ressources/img/icons/location-dot-solid.svg" class="iconsLocation">
                <input placeholder="Où allons-nous ?" type="text" name="nomCommuneArrivee" class="nomCommuneArrivee">
                <button type="submit" id='searchButton'value="Calculer"><img src="../ressources/img/icons/location-arrow-solid.svg" class="icons"></button>
            </div>


            <?php if (!empty($_POST)) { ?>
                <p>
                    Le plus court chemin entre <?= $nomCommuneDepart ?> et <?= $nomCommuneArrivee ?> mesure <?= $distance ?>km.
                </p>
            <?php } ?>
        </form>

    </div>
    <!--<img src="../ressources/img/3d-illustration-travel-location.png" class="imgGlobe">-->

