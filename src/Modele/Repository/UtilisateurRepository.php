<?php

namespace Explore\Modele\Repository;


use Exception;
use Explore\Modele\DataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository implements UtilisateurRepositoryInterface
{

    public function __construct(ConnexionBaseDeDonneesInterface $connexionBaseDeDonnees)
    {
        parent::__construct($connexionBaseDeDonnees);
    }


    public function construireDepuisTableau(array $utilisateurTableau): Utilisateur
    {
        return new Utilisateur(
            $utilisateurTableau["login"],
            $utilisateurTableau["mdp_hache"],
            $utilisateurTableau["email"],
            $utilisateurTableau["profilepicturename"]
        );
    }

    public function getNomTable(): string
    {
        return 'usersExplore';
    }

    protected function getNomClePrimaire(): string
    {
        return 'login';
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "mdp_hache",  "email", "profilePictureName"];
    }

    public function ajouterUserAValider(Utilisateur $user): bool
    {
        //on verifie si un user n'est pas deja en cours de validation
        $sql = "SELECT login from usersexploreavalider WHERE login = :loginTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin(),
        );

        $pdoStatement->execute($values);
        $result = $pdoStatement->fetch();
        if($result != null)
        {
            return false;
        }

        $sql = "INSERT INTO usersexploreavalider VALUES (:loginTag,:nonceTag, :emailTag)";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin(),
            "nonceTag" => $this->genererNonce(),
            "emailTag" => $user->getEmail()
        );

        $pdoStatement->execute($values);
        return true;

    }

    public function retirerUserAValider(Utilisateur $user): bool
    {
        //on verifie si un l'user est dans la table
        $sql = "SELECT login from usersexploreavalider WHERE login = :loginTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin(),
        );

        $pdoStatement->execute($values);
        $result = $pdoStatement->fetch();
        if($result == null)
        {
            return false;
        }

        $sql = "DELETE FROM usersexploreavalider WHERE login = :loginTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin()
        );

        $pdoStatement->execute($values);
        return true;
        //l'user a bien été enlevé

    }

    function genererNonce():string {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, 9)];
        }
        return $randomString;
    }

    public function getNonce($user): ?int
    {
        $sql = "SELECT nonce FROM usersexploreavalider WHERE login = :loginTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin()
        );

        $pdoStatement->execute($values);

        $result = $pdoStatement->fetch();
        if($result != null)
        {
            return $result['nonce'];
        }
        else return null;
    }

    public function estAdmin($user)
    {
        $sql = "SELECT login FROM usersadmin WHERE login = :loginTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $user->getLogin()
        );

        $pdoStatement->execute($values);

        $result = $pdoStatement->fetch();
        return $result != null;
    }
    public function getHistorique(string $login){
        $requeteSQL = <<<SQL
        SELECT idtrajet, points FROM historiquetrajets
        WHERE idlogin=:login;
        SQL;
        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($requeteSQL);

        $pdoStatement->execute(['login' => $login]);

        $tab = [];
        foreach ($pdoStatement as $tabId) {
            $tab[] = $tabId['idtrajet'];
        }

        return $tab;
    }

    public function updatePP($login, $ppName){
        $sql = "UPDATE usersexplore SET profilePictureName = :profilePicture WHERE login = :loginTag";

        $pdoStatement = $this->connexionBaseDeDonnees->getPdo()->prepare($sql);

        $values = array(
            "loginTag" => $login,
            "profilePicture" => $ppName
        );


        $pdoStatement->execute($values);

        $pdoStatement->execute($values);
    }
}