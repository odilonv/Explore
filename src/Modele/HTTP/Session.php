<?php

namespace App\PlusCourtChemin\Modele\HTTP;

use App\PlusCourtChemin\Configuration\Configuration;
use Exception;

class Session
{
    private static ?Session $instance = null;

    private function __construct()
    {
        if (session_start() === false) {
            throw new Exception("La session n'a pas réussi à démarrer.");
        }
    }

    public function verifierDerniereActivite(int $dureeExpiration)
    {
        if ($dureeExpiration == 0)
            return;

        if (isset($_SESSION['derniereActivite']) && ((time() - $_SESSION['derniereActivite']) > $dureeExpiration)) {
            $delai = time() - $_SESSION['derniereActivite'];
            session_unset();     // unset $_SESSION variable for the run-time
            // MessageFlash::ajouter("info", "Déconnexion : inactif depuis $delai sec au lieu $dureeExpiration");
        }

        if (isset($_SESSION['derniereActivite'])) {
            $delai = (time() - $_SESSION['derniereActivite']);
            // MessageFlash::ajouter("info", "Dernière activité il y a $delai sec.");
        }

        $_SESSION['derniereActivite'] = time(); // update last activity time stamp
    }

    public static function getInstance(): Session
    {
        if (is_null(Session::$instance)) {
            Session::$instance = new Session();
            $dureeExpiration = Configuration::getDureeExpirationSession();
            Session::$instance->verifierDerniereActivite($dureeExpiration);
        }
        return Session::$instance;
    }

    public function existeCle($cle): bool
    {
        return isset($_SESSION[$cle]);
    }

    public function enregistrer(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    public function lire(string $name): mixed
    {
        return $_SESSION[$name];
    }

    public function supprimer($name): void
    {
        unset($_SESSION[$name]);
    }

    public function detruire(): void
    {
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
        Cookie::supprimer(session_name()); // deletes the session cookie
        // Il faudra reconstruire la session au prochain appel de getInstance()
        $instance = null;
    }
}
