<?php

use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Generator\UrlGenerator;

/** @var UrlGenerator $generateur */
/** @var UrlHelper $assistant */
?>
<section class="fond-page">
    <form class="box-inscription" style="height: 250px;width:300px" method="POST" action="../web/validation">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>
        <label for="nonce" id="inputNonce" class="labelresponsive">Code de validation
            <input style="width:100px" class="inputForm" type="text" placeholder="123 456" name="nonce" id="nonce" value="">
        </label>

        <input type='hidden' name='action' value='creerDepuisFormulaire'>
        <input type='hidden' name='controleur' value='utilisateur'>

        <input class="inscriptionButton" type="submit" value="Valider l'inscription" />


        <br>
        <a id="renvoyerCode" href="../web/renvoyerCode">Renvoyer un code</a>

    </form>
</section>