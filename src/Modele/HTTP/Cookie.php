<?php

namespace App\PlusCourtChemin\Modele\HTTP;

class Cookie
{

    public static function existeCle($cle) : bool {
        return isset($_COOKIE[$cle]);
    }

    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        $valeurJSON = serialize($valeur);
        if ($dureeExpiration === null)
            setcookie($cle, $valeurJSON, 0);
        else
            setcookie($cle, $valeurJSON, time() + $dureeExpiration);
    }

    public static function lire(string $cle): mixed
    {
        return unserialize($_COOKIE[$cle]);
    }

    public static function supprimer($cle) : void
    {
        unset($_COOKIE[$cle]);
        setcookie($cle, "", 1);
    }
}
