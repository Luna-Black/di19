<?php
namespace src\Controller;

use src\Model\Article;
use src\Model\Bdd;
use src\Model\User;

class ApiController {

    public function ArticleGet($token)
    {
        $SQLUser = new User();
        $usersList = $SQLUser->SqlGetAll(Bdd::GetInstance());
        $tokenFound = false;
        foreach ($usersList as $user){
            if($token == $user->getTokenApi()) {
                $tokenFound = true;
            }
        }
        if($tokenFound) {
            $article = new Article();
            $listArticle = $article->SqlGet5LastValidated(Bdd::GetInstance());
            $articlesJson = json_encode($listArticle);
            return $articlesJson;
        }else{
            return 'token invalide';
        }
    }

    public function generateToken($username) {
        $SQLUser = new User();
        $user = $SQLUser->SqlGet(Bdd::GetInstance(), $username);
        $token = bin2hex(random_bytes(32));
        $user->setTokenApi($token);
        $user->SqlUpdateToken(Bdd::GetInstance());
        $_SESSION['login']['TokenApi'] = $token;
        header('Location:/Userpage/'.$user->getPseudo());
    }

    public function ArticlePost()
    {
        $article = new Article();
        $article->setTitre($_POST['Titre'])
            ->setDescription($_POST['Description'])
            ->setAuteur($_POST['Auteur'])
            ->setDateAjout($_POST['DateAjout'])
        ;
        $result = $article->SqlAdd(Bdd::getInstance());

        return json_encode($result);
    }

    public function ArticlePut($idArticle,$json)
    {
        $jsonData = json_decode($json);
        $articleSQL = new Article();
        $article = $articleSQL->SqlGet(BDD::getInstance(), $idArticle);
        if(isset($jsonData->Titre)){$article->setTitre($jsonData->Titre);}
        if(isset($jsonData->Description)){$article->setDescription($jsonData->Description);}
        if(isset($jsonData->Auteur)){$article->setAuteur($jsonData->Auteur);}
        if(isset($jsonData->DateAjout)){$article->setDateAjout($jsonData->DateAjout);}

        $result = $article->SqlUpdate(BDD::getInstance());

        return json_encode($result);

    }

}


