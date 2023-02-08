<?php

echo <<<HTML
<a href="?action=plusCourtChemin&controleur=noeudCommune">Calculer un plus court chemin</a>

<h3>Liste des noeuds communes :</h3>
<ul>
HTML;

foreach ($noeudsCommunes as $noeudCommune) {
    echo '<li>';
    require __DIR__ . "/detail.php";
    echo " <a href=\"?action=afficherDetail&controleur=noeudCommune&gid={$noeudCommune->getGid()}\">(Détail)</a>";
    echo '</li>';
}
echo "</ul>\n";