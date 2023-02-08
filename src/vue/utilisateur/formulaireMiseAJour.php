<div>
    <form method="<?= $method ?>" action="controleurFrontal.php">
        <fieldset>
            <legend>Mon formulaire :</legend>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="login_id">Login&#42;</label>
                <input class="InputAddOn-field" type="text" value="<?= $loginHTML ?>" placeholder="Ex : rlebreton" name="login" id="login_id" readonly>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="prenom_id">Prenom&#42;</label>
                <input class="InputAddOn-field" type="text" value="<?= $prenomHTML ?>" placeholder="Ex : Romain" name="prenom" id="prenom_id" required>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="nom_id">Nom&#42;</label>
                <input class="InputAddOn-field" type="text" value="<?= $nomHTML ?>" placeholder="Ex : Lebreton" name="nom" id="nom_id" required>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="email_id">Email&#42;</label>
                <input class="InputAddOn-field" type="email" value="<?= $emailHTML ?>" placeholder="rlebreton@yopmail.com" name="email" id="email_id" required>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="mdp_id">Ancien mot de passe&#42;</label>
                <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdpAncien" id="mdp_id" required>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="mdp_id">Nouveau mot de passe&#42;</label>
                <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp" id="mdp_id" required>
            </p>
            <p class="InputAddOn">
                <label class="InputAddOn-item" for="mdp2_id">Vérification du nouveau mot de passe&#42;</label>
                <input class="InputAddOn-field" type="password" value="" placeholder="" name="mdp2" id="mdp2_id" required>
            </p>
            <?php

            use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

            if (ConnexionUtilisateur::estAdministrateur()) {
            ?>
                <p class="InputAddOn">
                    <label class="InputAddOn-item" for="estAdmin_id">Administrateur</label>
                    <input class="InputAddOn-field" type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id" <?= $estAdmin ? "checked" : "" ?>>
                </p>
            <?php } ?>
            <input type='hidden' name='action' value='mettreAJour'>
            <input type='hidden' name='controleur' value='utilisateur'>
            <p class="InputAddOn">
                <input class="InputAddOn-field" type="submit" value="Envoyer" />
            </p>
        </fieldset>
    </form>
</div>