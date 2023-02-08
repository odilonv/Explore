<?php

namespace App\PlusCourtChemin\Modele\DataObject;

use App\PlusCourtChemin\Lib\MotDePasse;

class Utilisateur extends AbstractDataObject
{

    private string $login;
    private string $nom;
    private string $prenom;
    private string $mdpHache;
    private bool $estAdmin;
    private string $email;
    private string $emailAValider;
    private string $nonce;

    public function __construct(
        string $login,
        string $nom,
        string $prenom,
        string $mdpHache,
        bool $estAdmin,
        string $email,
        string $emailAValider,
        string $nonce,
    ) {
        $this->login = $login;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->mdpHache = $mdpHache;
        $this->estAdmin = $estAdmin;
        $this->email = $email;
        $this->emailAValider = $emailAValider;
        $this->nonce = $nonce;
    }

    public static function construireDepuisFormulaire (array $tableauFormulaire) : Utilisateur
    {
        return new Utilisateur(
            $tableauFormulaire["login"],
            $tableauFormulaire["nom"],
            $tableauFormulaire["prenom"],
            MotDePasse::hacher($tableauFormulaire["mdp"]),
            isset($tableauFormulaire["estAdmin"]),
            "",
            $tableauFormulaire["email"],
            MotDePasse::genererChaineAleatoire(),
        );
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getMdpHache(): string
    {
        return $this->mdpHache;
    }

    public function setMdpHache(string $mdpClair) {
        $this->mdpHache = MotDePasse::hacher($mdpClair);
    }

    public function getEstAdmin(): string
    {
        return $this->estAdmin;
    }

    public function setEstAdmin(string $estAdmin): void
    {
        $this->estAdmin = $estAdmin;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmailAValider(): string
    {
        return $this->emailAValider;
    }

    public function setEmailAValider(string $emailAValider): void
    {
        $this->emailAValider = $emailAValider;
    }

    public function getNonce(): string
    {
        return $this->nonce;
    }

    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }

    public function exporterEnFormatRequetePreparee(): array
    {
        return array(
            "login_tag" => $this->login,
            "nom_tag" => $this->nom,
            "prenom_tag" => $this->prenom,
            "mdp_hache_tag" => $this->mdpHache,
            "est_admin_tag" => $this->estAdmin ? "1" : "0",
            "email_tag" => $this->email,
            "nonce_tag" => $this->nonce,
            "email_a_valider_tag" => $this->emailAValider,
        );
    }
}
