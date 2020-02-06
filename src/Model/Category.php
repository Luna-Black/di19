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
            $requete = $bdd->prepare('DELETE FROM categories where Id = :idCategorie');
            $requete ->execute([
                'idCategorie'=>$idCategory
            ]);
            return true;
        }catch (\Exception $e){
            return false;
        }

    }

    public function SqlUpdate(\PDO $bdd) {
        try {
            $requete = $bdd->prepare('UPDATE categories set Nom=:Nom WHERE Id=:IdCategory');
            $requete->execute([
                'Nom'=> $this->getNom(),
                'IdCategory'=> $this->getId()
            ]);
            return array("0", "[OK] Update");
        }catch (\Exception $e){
            return array("1", "[ERREUR]".$e->getMessage());
        }

    }

    public function SqlGetAll(\PDO $bdd) {
        $requete = $bdd->prepare('SELECT * FROM categories');
        $requete->execute();
        $arrayCategory = $requete->fetchAll();

        $listCategory = [];
        foreach ($arrayCategory as $categorySQL){
            $category = new Category();
            $category->setId($categorySQL['Id']);
            $category->setNom($categorySQL['Nom']);

            $listCategory[] = $category;
        }
        return $listCategory;

    }

    public function SqlGet(\PDO $bdd,$idCategory){
        $requete = $bdd->prepare(
            'SELECT * FROM categories
            WHERE categories.Id = :idCategorie'
        );
        $requete->execute([
            'idCategorie' => $idCategory
        ]);

        $datas = $requete->fetch();

        $categorie = new Category();
        $categorie->setId($datas['Id']);
        $categorie->setNom($datas['Nom']);

        return $categorie;
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
    public function getNom()
    {
        return $this->Nom;
    }

    /**
     * @param mixed $Nom
     */
    public function setNom($Nom)
    {
        $this->Nom = $Nom;
    }


}