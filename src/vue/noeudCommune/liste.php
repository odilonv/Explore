<section class="fond-page">
    <ul class="box-liste">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>
        <h1>Noeuds Communes</h1>
        <?php
        foreach ($noeudsCommunes as $noeudCommune) {
            echo '<li>
            <span>Commune de <strong><?= $noeudCommune->getNomCommune() ?></strong></span>
            <span>
                Gid : <strong><?= $noeudCommune->getGid() ?></strong>,
                Identifiant Route : <strong><?= $noeudCommune->getId_rte500() ?></strong>
            </span>';
        }
        echo "</ul>\n";
        ?>
</section>