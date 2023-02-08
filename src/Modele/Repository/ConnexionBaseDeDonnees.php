<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Configuration\Configuration;
use App\PlusCourtChemin\Configuration\ConfigurationBDDPostgreSQL;
use PDO;

class ConnexionBaseDeDonnees
{
    private static ?ConnexionBaseDeDonnees $instance = null;

    private PDO $pdo;

    public static function getPdo(): PDO
    {
        return ConnexionBaseDeDonnees::getInstance()->pdo;
    }

    private function __construct()
    {
        $configuration = new Configuration(new ConfigurationBDDPostgreSQL());
        $configurationBDD = $configuration->getConfigurationBDD();

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

    private static function getInstance(): ConnexionBaseDeDonnees
    {
        if (is_null(ConnexionBaseDeDonnees::$instance))
            ConnexionBaseDeDonnees::$instance = new ConnexionBaseDeDonnees();
        return ConnexionBaseDeDonnees::$instance;
    }
}