<!--<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?/*= $pagetitle */?></title>
    <link rel="stylesheet" href="../ressources/css/navstyle.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li>
                    <a href="controleurFrontal.php?action=afficherListe&controleur=utilisateur">Utilisateurs</a>
                </li>
                <li>
                    <a href="controleurFrontal.php?action=afficherListe&controleur=noeudCommune">Communes</a>
                    <br>
                </li>
                <?php
/*
                use App\PlusCourtChemin\Lib\ConnexionUtilisateur;

                if (!ConnexionUtilisateur::estConnecte()) {
                    echo <<<HTML
                    <li>
                        <a href="controleurFrontal.php?action=afficherFormulaireConnexion&controleur=utilisateur">
                            <img alt="login" src="../ressources/img/enter.png" width="18">
                        </a>
                    </li>
                    HTML;
                } else {
                    $loginHTML = htmlspecialchars(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                    $loginURL = rawurlencode(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                    echo <<<HTML
                    <li>
                        <a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login=$loginURL">
                            <img alt="user" src="../ressources/img/user.png" width="18">
                            $loginHTML
                        </a>
                    </li>
                    <li>
                        <a href="controleurFrontal.php?action=deconnecter&controleur=utilisateur">
                            <img alt="logout" src="../ressources/img/logout.png" width="18">
                        </a>
                    </li>
                    HTML;
                }
                */?>
            </ul>
        </nav>
        <div>
            <?php
/*            foreach (["success", "info", "warning", "danger"] as $type) {
                foreach ($messagesFlash[$type] as $messageFlash) {
                    echo <<<HTML
                    <div class="alert alert-$type">
                        $messageFlash
                    </div>
                    HTML;
                }
            }
            */?>
        </div>
    </header>
    <main>
        <?php
/*        /**
         * @var string $cheminVueBody
         */
        /*require __DIR__ . "/{$cheminVueBody}";
        */?>
    </main>
    <footer>
        <p>
            Copyleft Romain Lebreton
        </p>
    </footer>
</body>

</html>-->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $pagetitle ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto:400,900&amp;display=swap');
    </style>
    <link rel="stylesheet" href="../ressources/css/main.css">
</head>
<body>
<ul class="nav">
    <li class="logo">Explor</li>
    <li><img src="../ressources/img/icons/user-solid.svg" alt="user" class="icons"></li>
    <li><img src="../ressources/img/icons/compass-solid.svg" alt="compass" class="icons" </li>
</ul>
<?php
        /**
         * @var string $cheminVueBody
         */
require __DIR__ . "/{$cheminVueBody}";
?>


</body>
</html>