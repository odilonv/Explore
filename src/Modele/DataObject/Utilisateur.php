<?php

namespace Explore\Modele\DataObject;

use Explore\Lib\MotDePasse;

class Utilisateur extends AbstractDataObject
{

    private string $login;
    private string $mdpHache;
    private string $email;
    private string $profilePictureName;

    public function __construct(
        string $login,
        string $mdpHache,
        string $email,
        string $profilePictureName
    ) {
        $this->login = $login;
        $this->mdpHache = $mdpHache;
        $this->email = $email;
        $this->profilePictureName = $profilePictureName;
    }

    public static function construireDepuisFormulaire (array $tableauFormulaire) : Utilisateur
    {
        return new Utilisateur(
            $tableauFormulaire["login"],
            MotDePasse::hacher($tableauFormulaire["mdp"]),
            $tableauFormulaire["email"],
            $tableauFormulaire["profilePictureName"]
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

    /**
     * @return string
     */
    public function getProfilePictureName(): string
    {
        return $this->profilePictureName;
    }

    /**
     * @param string $profilePictureName
     */
    public function setProfilePictureName(string $profilePictureName): void
    {
        $this->profilePictureName = $profilePictureName;
    }



    public function exporterEnFormatRequetePreparee(): array
    {
        return array(
            "login_tag" => $this->login,
            "mdp_hache_tag" => $this->mdpHache,
            "email_tag" => $this->email,
            "profilePictureName_tag" => $this->profilePictureName,
        );
    }


}
