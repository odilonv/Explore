<?php

namespace Explore\Configuration;

use Exception;
use PDO;

class ConfigurationBDDPostgreSQL implements ConfigurationBDDInterface
{
    private string $nomBDD = "iut";
    private string $hostname = "162.38.222.142";
    private string $port = "5673";

    public function getLogin(): string
    {
        return "souvignetn";
    }

    public function getMotDePasse(): string
    {
        return "060781121EK";
    }

    public function getDSN() : string{
        return "pgsql:host={$this->hostname};port={$this->port};dbname={$this->nomBDD};options='--client_encoding=UTF8'";
    }

    public function getOptions() : array {
        return array();
    }
}