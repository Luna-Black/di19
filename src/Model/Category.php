<?php
namespace src\Model;

class Category {
    private $Id;
    private $Nom;

    public function SqlAdd(\PDO $bdd) {
        try {
            $requete = $bdd->prepare('INSERT INTO categories (Nom) VALUES(:Nom)');
            $requete->execute([
                "Nom"=> $this->getNom(),
            ]);
            return array("result"=>true,"message"=>$bdd->lastInsertId());
        }catch (\Exception $e){
            return array("result"=>false,"message"=>$e->getMessage());
        }

    }

    public function SqlDelete(\PDO $bdd,$idCategory) {
        try {
            $requete = $bdd->prepare('DELETE FROM categories where Id = :idCategory');
            $requete ->execute([
                'idCategory'=>$idCategory
            ]);
            return true;
        }catch (\Exception $e){
            return false;
        }

    }

    public function SqlUpdate(\PDO $bdd) {
        try {
            $requete = $bdd->prepare('UPDATE categories set Nom=:Nom');
            $requete->execute([
                'Nom'=> $this->getNom()
            ]);
            return array("0", "[OK] Update");
        }catch (\Exception $e){
            return array("1", "[ERREUR]".$e->getMessage());
        }

    }

    public function SqlGetAll(\PDO $bdd) {
        $requete = $bdd->prepare()

    }
}