<?php
/** @var \App\PlusCourtChemin\Modele\DataObject\Utilisateur $utilisateur */

use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

$login = $utilisateur->getLogin();
$loginHTML = htmlspecialchars($login);
$prenomHTML = htmlspecialchars($utilisateur->getPrenom());
$nomHTML = htmlspecialchars($utilisateur->getNom());
$loginURL = rawurlencode($login);
?>

<p>
    Utilisateur <?= "$prenomHTML $nomHTML" ?> de login <?= $loginHTML ?>

    <?php if (ConnexionUtilisateur::estUtilisateur($login) || ConnexionUtilisateur::estAdministrateur()) { ?>
    <a href="controleurFrontal.php?action=afficherFormulaireMiseAJour&controleur=utilisateur&login=<?= $loginURL ?>">(mettre à jour)</a>
    <a href="controleurFrontal.php?action=supprimer&controleur=utilisateur&login=<?= $loginURL ?>">(supprimer)</a>
    <?php } ?>
</p>