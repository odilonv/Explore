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





<section id="search">
    <div class="contain">
            <form action="" class="insideDivide" method="post">
            <div class="underlineTravel"><img src="../ressources/img/icons/circle-solid.svg" class="iconsLocationStart"><input placeholder="Une ville de départ ? Ex: Montpellier" name="nomCommuneDepart" type="text"></div>
            <div class="circles">
                <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition"><img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
                <img src="../ressources/img/icons/circle-solid.svg" class="circleTransition">
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

<footer>
    <?php
    use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

    if (!ConnexionUtilisateur::estConnecte()) {
        echo <<<HTML
                            
                                <a href="controleurFrontal.php?action=afficherFormulaireConnexion&controleur=utilisateur" id="connectFooter">
                                    <img src="../ressources/img/icons/user-solid.svg" class="icons">
                                    <h2>Connexion</h2>
                                </a>
                            
                            HTML;
    } else {
        $loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        $loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        echo <<<HTML
                                <a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL" id="connectFooter">
                                    <img src="../ressources/img/icons/user-solid.svg" class="icons">
                                    <h2>$loginHTML</h2>
                                </a>
                            HTML;
    }
    ?>
    <div id="echelleFooter">
        <a href=""><img src="../ressources/img/icons/minus-solid.svg" class="icons"></a>
        <a href=""><img src="../ressources/img/icons/plus-solid.svg" class="icons"></a>
    </div>
    <a id="signFooter" href="">
        <h2>Explore</h2>
    </a>
</footer>
