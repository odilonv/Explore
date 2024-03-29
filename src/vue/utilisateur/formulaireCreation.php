
<?php

use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Generator\UrlGenerator;

/** @var UrlGenerator $generateur */
/** @var UrlHelper $assistant */
?>
<section class="fond-page">
    <form style="height:600px" class="box-inscription" method="POST" action="../web/inscription" enctype="multipart/form-data">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>
        <label for="login_id" class="labelresponsive">Login&#42;
            <input type="text" placeholder="Lebreton" name="login" id="login_id" value="">
        </label>

        <label for="email_id" class="labelresponsive">Email&#42;
            <input class="InputAddOn-field" type="email" value="" placeholder="rlebreton@yopmail.com" name="email" id="email_id" required>
        </label>

        <label for="mdp_id" class="labelresponsive">Mot de passe&#42;
            <input class="InputAddOn-field" type="password" value="" placeholder="********" name="mdp" id="mdp_id" required>
        </label>
        <label for="mdp2_id" class="labelresponsive">Vérification du mot de passe&#42;
            <input class="InputAddOn-field" type="password" value="" placeholder="********" name="mdp2" id="mdp2_id" required>
        </label>

        <label for="avatar" class="labelresponsive">Photo de profil
            <input type="file" id="profilePictureInput" name="profilePicture"  accept="image/png, image/jpeg, image/jpg">
        </label>


        <input type='hidden' name='action' value='creerDepuisFormulaire'>
        <input type='hidden' name='controleur' value='utilisateur'>

        <input class="inscriptionButton" type="submit" value="S'inscrire" />

    </form>
</section>