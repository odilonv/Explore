<?php

namespace Explore\Modele\Repository;
use Explore\Configuration\ConfigurationBDDInterface;
use PDO;


class ConnexionBaseDeDonnees implements ConnexionBaseDeDonneesInterface
{
    private PDO $pdo;

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function __construct(ConfigurationBDDInterface $configurationBDD)
    {

        // Connexion à la base de données
        $this->pdo = new PDO(
            $configurationBDD->getDSN(),
            $configurationBDD->getLogin(),
            $configurationBDD->getMotDePasse(),
            $configurationBDD->getOptions()
        );


        // On active le mode d'affichage des erreurs, et le lancement d'exception en cas d'erreur
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
