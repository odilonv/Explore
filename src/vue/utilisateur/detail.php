<?php

/** @var \Explore\Modele\DataObject\Utilisateur $utilisateur */

use Explore\Lib\ConnexionUtilisateur;

$login = $utilisateur->getLogin();
$loginHTML = htmlspecialchars($login);
$loginURL = rawurlencode($login);
$picture = $utilisateur->getProfilePictureName();
?>
<section class="fond-page">
    <form class="box-inscription" style="height: 250px;width:300px" method="POST" action="../web/validation">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>


        <h2><?php echo $login?></h2>
        <br>
        <img id="profilePicture" src="../ressources/img/utilisateurs/<?php echo $picture ?>">



    </form>
</section>