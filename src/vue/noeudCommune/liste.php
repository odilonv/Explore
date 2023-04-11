<section class="fond-page">
    <ul class="box-liste">
        <div class="exitLine">
            <a class="exitButton" href="<?= $generateur->generate("plusCourt"); ?>"><img class="icons" src="<?= $assistant->getAbsoluteUrl("ressources/img/icons/xmark-solid.svg"); ?>" alt="exit"></a>
        </div>
        <h1>Noeuds Communes</h1>
        <?php
        foreach ($noeudsCommunes as $noeudCommune) {
            echo '<li class="linoeudsCommunes">';
            require __DIR__ . "/detail.php";
            echo '</li>';
        }
        echo "</ul>\n";
        ?>
</section>