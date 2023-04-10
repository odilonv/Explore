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
    <form class="box-inscription" method="<?= $method ?>" action="controleurFrontal.php">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>
        <label for="login_id">Login&#42;
            <input type="text" placeholder="Ex : rlebreton" name="login" id="login_id" value="<?php echo generateRandomString() ?>">
        </label>

        <label for="email_id">Email&#42;
            <input class="InputAddOn-field" type="email" value="<?php echo generateRandomString() . '@' . generateRandomString(3) . '.com' ?>" placeholder="rlebreton@yopmail.com" name="email" id="email_id" required>
        </label>

        <label for="mdp_id">Mot de passe&#42;
            <input class="InputAddOn-field" type="password" value="<?php echo 'motDePasse123' ?>" placeholder="" name="mdp" id="mdp_id" required>
        </label>
        <label for="mdp2_id">Vérification du mot de passe&#42;
            <input class="InputAddOn-field" type="password" value="<?php echo 'motDePasse123' ?>" placeholder="" name="mdp2" id="mdp2_id" required>
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

        <input class="inscriptionButton" type="submit" value="S'inscrire" />

    </form>
</section>