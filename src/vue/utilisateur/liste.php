<?php

use Explore\Modele\DataObject\Utilisateur;

?>

<section class="fond-page">
    <ul class="box-liste">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>
        <h1>Utilisateurs</h1>
        <?php
        /** @var Utilisateur[] $utilisateurs */
        foreach ($utilisateurs as $utilisateur) {
            $loginHTML = htmlspecialchars($utilisateur->getLogin());
            $loginURL = rawurlencode($utilisateur->getLogin());
            echo '
                <li>
                    <a class="lienProfil" href="'.$generateur->generate("afficherDetail", ["loginUser" => $loginHTML]).'">'.$loginHTML.'</a>
                </li>';

        }
        echo "</ul>\n";
        ?>
</section>