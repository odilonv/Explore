<?php
echo "<h3>Liste des utilisateurs :</h3>\n";
echo "<ul>\n";
/** @var \App\PlusCourtChemin\Modele\DataObject\Utilisateur[] $utilisateurs */
foreach ($utilisateurs as $utilisateur) {
    $loginHTML = htmlspecialchars($utilisateur->getLogin());
    $loginURL = rawurlencode($utilisateur->getLogin());
    echo <<< HTML
        <li>
            Utilisateur de login $loginHTML
            <a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL">(+ d'info)</a>
        </li>\n
    HTML;
}
echo "</ul>\n";
echo "<a href='controleurFrontal.php?action=afficherFormulaireCreation&controleur=utilisateur'>Créer un utilisateur</a>\n";