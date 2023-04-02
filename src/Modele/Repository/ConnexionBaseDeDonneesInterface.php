<?php

namespace Explore\Modele\Repository;
use PDO;

interface ConnexionBaseDeDonneesInterface
{
    public function getPdo(): PDO;

}