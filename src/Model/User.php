<?php
namespace src\Model;

class User {
    private $Id;
    private $Email;
    private $Mdp;
    private $Pseudo;
    private $Role;



    public function SqlAdd(\PDO $bdd) {
        try{
            $requete = $bdd->prepare('INSERT INTO utilisateurs (Pseudo,Email,Mdp,Id_roles) VALUES (:Pseudo,:Email,:Mdp,:Valide,3)');
            $requete->execute([
                "Email"=>$this->getEmail(),
                "Mdp"=>$this->getMdp(),
                "Pseudo"=>$this->getPseudo(),
            ]);
            return array("result"=>true,"message"=>$bdd->lastInsertId());
        }catch (\Exception $e){
            return array("result"=>false,"message"=>$e->getMessage());
        }
    }





    public function SqlUpdate() {

    }

    public function SqlGetAll() {

    }

    public function SqlGet(\PDO $bdd) {
        $requete = $bdd->prepare(
            'SELECT utilisateurs.Id as UserID, Pseudo, Mdp, Email, roles.Nom as role FROM utilisateurs
            INNER JOIN roles on utilisateurs.Id_roles = roles.Id
            WHERE Email = :email'
        );
        $requete->execute([
            "email" => $this->getEmail()
        ]);

        $data = $requete->fetch();

        $user = new User();
        $user->setId($data['UserID']);
        $user->setPseudo($data['Pseudo']);
        $user->setMdp($data['Mdp']);
        $user->setEmail($data['Email']);
        $user->setRole($data['role']);

        return $user;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * @param mixed $Id
     */
    public function setId($Id)
    {
        $this->Id = $Id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * @param mixed $Email
     */
    public function setEmail($Email)
    {
        $this->Email = $Email;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->Mdp;
    }

    /**
     * @param mixed $Mdp
     */
    public function setMdp($Mdp)
    {
        $this->Mdp = $Mdp;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->Pseudo;
    }

    /**
     * @param mixed $Pseudo
     */
    public function setPseudo($Pseudo)
    {
        $this->Pseudo = $Pseudo;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->Role;
    }

    /**
     * @param mixed $Role
     */
    public function setRole($Role)
    {
        $this->Role = $Role;
    }

    /**
     * @return mixed
     */
    public function getValide()
    {
        return $this->Valide;
    }

    /**
     * @param mixed $Valide
     */
    public function setValide($Valide)
    {
        $this->Valide = $Valide;
    }


}