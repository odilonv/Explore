<?php

/** @var \Explore\Modele\DataObject\Utilisateur $utilisateur */

use Explore\Lib\ConnexionUtilisateur;

$login = $utilisateur->getLogin();
$loginHTML = htmlspecialchars($login);
$loginURL = rawurlencode($login);
$picture = $utilisateur->getProfilePictureName();
?>
<section class="fond-page">

    <form class="box-inscription" style="min-height: 250px;width:300px" method="POST" enctype="multipart/form-data" action="<?= $generateur->generate("mettreAJour"); ?>">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>


        <h2><?php echo $login?></h2>
        <br>
        <img id="profilePicture" src="<?= $assistant->getAbsoluteUrl("ressources/img/utilisateurs/".$picture)?>">


        <?php
        if(ConnexionUtilisateur::getLoginUtilisateurConnecte() == $login)
        {
            echo '
                <br>
                    <label id="changerPP" for="avatar" style="margin:0" value="Changer la photo de profil">
                        <input type="file" id="profilePictureInput" name="profilePicture"  accept="image/png, image/jpeg, image/jpg">
                    </label>
                    <br>
                    <input class="inscriptionButton" type="submit" value="Modifier" />
                
            ';
        }

        if(ConnexionUtilisateur::getLoginUtilisateurConnecte() == $login || ConnexionUtilisateur::estAdministrateur())
        {
            echo '
            <br>
            <a class="lienProfil" href="'.$generateur->generate("supprimerUser", ["loginUser" => $login]).'"> Supprimer le profil </a>
            ';
        }
        ?>

    </form>
</section>