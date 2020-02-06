<?php
namespace src\Model;

class Article extends Contenu implements \JsonSerializable {
    private $Auteur;
    private $DateAjout;
    private $ImageRepository;
    private $ImageFileName;


    public function firstXwords($nb){
        $phrase = $this->getDescription();
        $arrayWord = str_word_count($phrase,1);

        return implode(" ",array_slice($arrayWord,0,$nb));
    }


    public function SqlAdd(\PDO $bdd) {
        try{
            $requete = $bdd->prepare('INSERT INTO articles (Titre, Description, DateAjout, Auteur, ImageRepository, ImageFileName, Id_statuts, Id_categories) VALUES(:Titre, :Description, :DateAjout, :Auteur, :ImageRepository, :ImageFileName, :Id_statuts, :Id_categories)');
            $requete->execute([
                "Titre" => $this->getTitre(),
                "Description" => $this->getDescription(),
                "DateAjout" => $this->getDateAjout(),
                "Auteur" => $this->getAuteur(),
                "ImageRepository" => $this->getImageRepository(),
                "ImageFileName" => $this->getImageFileName(),
                "Id_statuts" => $this->getStatut(),
                "Id_categories" => $this->getCategorie()
            ]);
            return array("result"=>true,"message"=>$bdd->lastInsertId());
        }catch (\Exception $e){
            return array("result"=>false,"message"=>$e->getMessage());
        }
    }

    public function SqlGetAll(\PDO $bdd){
            $requete = $bdd->prepare(
                'SELECT articles.Id as articleID, Titre, Auteur, Description, DateAjout, ImageRepository, ImageFileName, statuts.Nom as statut, categories.Nom as categorie
                FROM articles
                INNER JOIN statuts on articles.Id_statuts = statuts.Id
                INNER JOIN categories on articles.Id_categories = categories.Id
                ORDER BY articleID ASC'
            );
            $requete->execute();
            $arrayArticle = $requete->fetchAll();

            $listArticle = [];
            foreach ($arrayArticle as $articleSQL){
                $article = new Article();
                $article->setId($articleSQL['articleID']);
                $article->setTitre($articleSQL['Titre']);
                $article->setAuteur($articleSQL['Auteur']);
                $article->setDescription($articleSQL['Description']);
                $article->setDateAjout($articleSQL['DateAjout']);
                $article->setImageRepository($articleSQL['ImageRepository']);
                $article->setImageFileName($articleSQL['ImageFileName']);
                $article->setStatut($articleSQL['statut']);
                $article->setCategorie($articleSQL['categorie']);

                $listArticle[] = $article;
            }
            return $listArticle;
    }
    public function SqlGet(\PDO $bdd,$idArticle){
        $requete = $bdd->prepare(
            'SELECT articles.Id as articleID, Titre, Auteur, Description, DateAjout, ImageRepository, ImageFileName, statuts.Nom as statut, categories.Nom as categorie
            FROM articles
            INNER JOIN statuts on articles.Id_statuts = statuts.Id
            INNER JOIN categories on articles.Id_categories = categories.Id
            WHERE articles.Id = :idArticle'
        );
        $requete->execute([
            'idArticle' => $idArticle
        ]);

        $datas =  $requete->fetch();

        $article = new Article();
        $article->setId($datas['articleID']);
        $article->setTitre($datas['Titre']);
        $article->setAuteur($datas['Auteur']);
        $article->setDescription($datas['Description']);
        $article->setDateAjout($datas['DateAjout']);
        $article->setImageRepository($datas['ImageRepository']);
        $article->setImageFileName($datas['ImageFileName']);
        $article->setStatut($datas['statut']);
        $article->setCategorie($datas['categorie']);

        return $article;
    }

    public function SqlSearch(\PDO $bdd, array $fields, $keyword){
        $conditionsArray = [];
        foreach($fields as $field){
            $conditionsArray[] = 'articles.'.$field.' like "%'.$keyword.'%" ';
        }
        $conditions = implode('OR ', $conditionsArray);
        $requete = $bdd->prepare(
            'SELECT articles.Id as articleID, Titre, Auteur, Description, DateAjout, ImageRepository, ImageFileName, statuts.Nom as statut, categories.Nom as categorie
            FROM articles
            INNER JOIN statuts on articles.Id_statuts = statuts.Id
            INNER JOIN categories on articles.Id_categories = categories.Id
            WHERE '.$conditions.'
            ORDER BY articleID ASC'
        );
        $requete->execute();
        $articlesArray = $requete->fetchAll();

        $articlesList = [];
        foreach($articlesArray as $SQLArticle){
            $article = new Article();
            $article->setId($SQLArticle['articleID']);
            $article->setTitre($SQLArticle['Titre']);
            $article->setAuteur($SQLArticle['Auteur']);
            $article->setDescription($SQLArticle['Description']);
            $article->setDateAjout($SQLArticle['DateAjout']);
            $article->setImageRepository($SQLArticle['ImageRepository']);
            $article->setImageFileName($SQLArticle['ImageFileName']);
            $article->setStatut($SQLArticle['statut']);
            $article->setCategorie($SQLArticle['categorie']);

            $articlesList[] = $article;
        }
        return $articlesList;
    }

    public function SqlUpdate(\PDO $bdd){
        try{
            $requete = $bdd->prepare(
                'UPDATE articles SET
                Titre=:Titre, 
                Description=:Description, 
                DateAjout=:DateAjout, 
                Auteur=:Auteur, 
                ImageRepository=:ImageRepository, 
                ImageFileName=:ImageFileName, 
                Id_categories=:Id_categories 
                WHERE id=:IDARTICLE'
            );
            $requete->execute([
                'Titre' => $this->getTitre()
                ,'Description' => $this->getDescription()
                ,'DateAjout' => $this->getDateAjout()
                ,'Auteur' => $this->getAuteur()
                ,'ImageRepository' => $this->getImageRepository()
                ,'ImageFileName' => $this->getImageFileName()
                ,'IdCategories' => $this->getCategorie()
                ,'IDARTICLE' => $this->getId()
            ]);
            return array("0", "[OK] Update");
        }catch (\Exception $e){
            return array("1", "[ERREUR] ".$e->getMessage());
        }
    }

    public function SqlUpdateStatus(\PDO $bdd){
        try{
            $requete = $bdd->prepare(
                'UPDATE articles 
                SET Id_statuts=:Id_statuts
                WHERE Id=:IDARTICLE'
            );
            $requete->execute([
                'Id_statuts' => $this->getStatut()
                ,'IDARTICLE' => $this->getId()
            ]);
            return array("0", "[OK] Update");
        }catch (\Exception $e){
            return array("1", "[ERREUR] ".$e->getMessage());
        }
    }

    public function SqlDelete (\PDO $bdd,$idArticle){
        try{
            $requete = $bdd->prepare('DELETE FROM articles where Id = :idArticle');
            $requete->execute([
                'idArticle' => $idArticle
            ]);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function SqlTruncate (\PDO $bdd){
        try{
            $requete = $bdd->prepare('TRUNCATE TABLE articles');
            $requete->execute();
            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function jsonSerialize()
    {
        return [
            'Id' => $this->getId()
            ,'Titre' => $this->getTitre()
            ,'Description' => $this->getDescription()
            ,'DateAjout' => $this->getDateAjout()
            ,'ImageRepository' => $this->getImageRepository()
            ,'ImageFileName' => $this->getImageFileName()
            ,'Auteur' => $this->getAuteur()
        ];
    }


    /**
     * @return mixed
     */
    public function getAuteur()
    {
        return $this->Auteur;
    }

    /**
     * @param mixed $Auteur
     * @return Article
     */
    public function setAuteur($Auteur)
    {
        $this->Auteur = $Auteur;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateAjout()
    {
        return $this->DateAjout;
    }

    /**
     * @param mixed $DateAjout
     * @return Article
     */
    public function setDateAjout($DateAjout)
    {
        $this->DateAjout = $DateAjout;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageRepository()
    {
        return $this->ImageRepository;
    }

    /**
     * @param mixed $ImageRepository
     * @return Article
     */
    public function setImageRepository($ImageRepository)
    {
        $this->ImageRepository = $ImageRepository;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageFileName()
    {
        return $this->ImageFileName;
    }

    /**
     * @param mixed $ImageFileName
     * @return Article
     */
    public function setImageFileName($ImageFileName)
    {
        $this->ImageFileName = $ImageFileName;
        return $this;
    }


}