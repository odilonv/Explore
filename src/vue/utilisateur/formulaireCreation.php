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





<section class="fond-page">
    <form class="box-inscription" method="post" action="../web/inscription">
        <div class="exitLine">
        <div class="exitButton"><img class="icons" src="../ressources/img/icons/xmark-solid.svg" alt="exit"></div>
        </div>
        <label for="login_id">Login&#42;
                <input type="text" placeholder="Ex : rlebreton" name="login" id="login_id"  value="<?php echo generateRandomString()?>">


            </label>
                    <!--
                        <label for="prenom_id">Prenom&#42;
                        <input type="text" value="" placeholder="Ex : Romain" name="prenom" id="prenom_id" required>
                        </label>

                        <label for="nom_id">Nom&#42;
                        <input class="InputAddOn-field" type="text" value="" placeholder="Ex : Lebreton" name="nom" id="nom_id" required>
                    </label>
                    -->


                <label for="email_id">Email&#42;
                <input class="InputAddOn-field" type="email" value="<?php echo generateRandomString().'@'.generateRandomString(3).'.com' ?>" placeholder="rlebreton@yopmail.com" name="email" id="email_id" required>
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