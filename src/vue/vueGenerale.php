<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $pagetitle ?></title>
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
                </li>
                <?php

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
                ?>
            </ul>
        </nav>
        <div>
            <?php
            foreach (["success", "info", "warning", "danger"] as $type) {
                foreach ($messagesFlash[$type] as $messageFlash) {
                    echo <<<HTML
                    <div class="alert alert-$type">
                        $messageFlash
                    </div>
                    HTML;
                }
            }
            ?>
        </div>
    </header>
    <main>
        <?php
        /**
         * @var string $cheminVueBody
         */
        require __DIR__ . "/{$cheminVueBody}";
        ?>
    </main>
    <footer>
        <p>
            Copyleft Romain Lebreton
        </p>
    </footer>
</body>

</html>