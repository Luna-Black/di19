<?php
namespace src\Model;

class User {
    private $Id;
    private $Email;
    private $Password;
    private $Name;
    private $Role;
    private $Valide;



    public function SqlAdd(\PDO $bdd) {
        try{
            $requete = $bdd->prepare('INSERT INTO utilisateurs (Pseudo,Email,Mdp,Valide,Id_roles) VALUES (:Pseudo,:Email,:Mdp,:Valide,2)');
            $requete->execute([
                "Email"=>$this->getEmail(),
                "Mdp"=>$this->getPassword(),
                "Pseudo"=>$this->getPseudo(),
                "Valide"=>$this->getValide()

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
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * @param mixed $Password
     */
    public function setPassword($Password)
    {
        $this->Password = $Password;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->Name;
    }

    /**
     * @param mixed $Name
     */
    public function setPseudo($Name)
    {
        $this->Name = $Name;
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