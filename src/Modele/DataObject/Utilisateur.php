<?php

namespace App\PlusCourtChemin\Modele\DataObject;

use App\PlusCourtChemin\Lib\MotDePasse;

class Utilisateur extends AbstractDataObject
{

    private string $idUser;
    private string $mdpHache;
    private string $emailUser;
    private string $nom;
    private string $prenom;
    private bool $estAdmin;


    public function __construct(
        string $idUser,
        string $mdpHache,
        string $email,
        string $nom,
        string $prenom,

        bool   $estAdmin
    ) {
        $this->idUser = $idUser;
        $this->mdpHache = $mdpHache;
        $this->emailUser = $email;
        $this->nom = $nom;
        $this->prenom = $prenom;

        $this->estAdmin = $estAdmin;
    }

    public static function construireDepuisFormulaire (array $tableauFormulaire) : Utilisateur
    {
        return new Utilisateur(
            $tableauFormulaire["idUser"],
            MotDePasse::hacher($tableauFormulaire["mdp"]),
            $tableauFormulaire["emailUser"],
            $tableauFormulaire["nom"],
            $tableauFormulaire["prenom"],

            isset($tableauFormulaire["estAdmin"])
        );
    }

    public function getIdUser(): string
    {
        return $this->idUser;
    }

    public function setIdUser(string $idUser): void
    {
        $this->idUser = $idUser;
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

    public function getEmailUser(): string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): void
    {
        $this->emailUser = $emailUser;
    }

    /*
    public function getEmailAValider(): string
    {
        return $this->emailAValider;
    }

    public function setEmailAValider(string $emailAValider): void
    {
        $this->emailAValider = $emailAValider;
    }
    */

    /*
    public function getNonce(): string
    {
        return $this->nonce;
    }

    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }
    */

    public function exporterEnFormatRequetePreparee(): array
    {
        return array(
            "idUser_tag" => $this->idUser,
            "mdp_hache_tag" => $this->mdpHache,
            "email_user_tag" => $this->emailUser,
            "nom_tag" => $this->nom,
            "prenom_tag" => $this->prenom,

            "est_admin_tag" => $this->estAdmin ? "1" : "0"
        );
    }
}
