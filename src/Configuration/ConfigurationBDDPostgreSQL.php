<?php

namespace App\PlusCourtChemin\Configuration;

use Exception;
use PDO;

class ConfigurationBDDPostgreSQL implements ConfigurationBDDInterface
{
    private string $nomBDD = "bdd iut";
    private string $hostname = "iut";

    public function getLogin(): string
    {
        return "esclapezl";
    }

    public function getMotDePasse(): string
    {
        return "070510757KF";
    }

    public function getDSN() : string{
        return "pgsql:host={$this->hostname};dbname={$this->nomBDD};options='--client_encoding=UTF8'";
    }

    public function getOptions() : array {
        return array();
    }
}
