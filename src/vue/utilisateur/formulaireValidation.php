<?php //pour debug, a supprimer plus tard
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>




<?php

use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Generator\UrlGenerator;

/** @var UrlGenerator $generateur */
/** @var UrlHelper $assistant */
?>
<section class="fond-page">
    <form class="box-inscription" style="height: 250px" method="POST" action="../web/validation">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>
        <label for="nonce">Code de validation
            <input class="inputForm" type="text" placeholder="123 456" name="nonce" id="nonce" value="">
        </label>


        <?php

        use Explore\Lib\ConnexionUtilisateur;

        if (ConnexionUtilisateur::estAdministrateur()) {
            ?>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="estAdmin_id">Administrateur</label>
                <input class="InputAddOn-field" type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id">
            </p>
        <?php } ?>


        <input type='hidden' name='action' value='creerDepuisFormulaire'>
        <input type='hidden' name='controleur' value='utilisateur'>

        <input class="inscriptionButton" type="submit" value="Valider l'inscription" />

    </form>
</section>