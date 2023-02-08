<?php

namespace App\PlusCourtChemin\Configuration;

use Exception;
use PDO;

class ConfigurationBDDPostgreSQL implements ConfigurationBDDInterface
{
    private string $nomBDD = "iut";
    private string $hostname = "162.38.222.142";

    public function getLogin(): string
    {
        throw new Exception("Login BDD non renseigné !");
    }

    public function getMotDePasse(): string
    {
        throw new Exception("Mot de passe BDD non renseigné !");
    }

    public function getDSN() : string{
        return "pgsql:host={$this->hostname};dbname={$this->nomBDD};options='--client_encoding=UTF8'";
    }

    public function getOptions() : array {
        return array();
    }
}
