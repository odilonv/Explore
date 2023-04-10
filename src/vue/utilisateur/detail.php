<?php

/** @var $utilisateur */

use Explore\Lib\ConnexionUtilisateur;

$login = $utilisateur->getLogin();
$loginHTML = htmlspecialchars($login);
$loginURL = rawurlencode($login);
?>
<section class="fond-page">
    <form class="box-inscription" style="height: 250px;width:300px" method="POST" action="../web/validation">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>


        <h2><?php echo $login?></h2>
        <br>
        <img id="notFound" src="../ressources/img/icons/face-grimace-solid.svg">



    </form>
</section>