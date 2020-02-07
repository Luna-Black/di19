<?php
namespace src\Controller;

use src\Model\Article;
use src\Model\Bdd;
use DateTime;
use src\Model\Category;

class ArticleController extends AbstractController {

    public function Index(){
        return $this->ListAll();
    }

    public function ListAll(){
        $article = new Article();
        $listArticle = $article->SqlGetByStatus(Bdd::GetInstance(), 1);

        //Lancer la vue TWIG
        return $this->twig->render(
            'Article/list.html.twig',[
                'articleList' => $listArticle
            ]
        );
    }

    public function listByStatus($status){
        UserController::checkPermission('articles', 'validate');
        if($_SESSION['login'])
        $article = new Article();
        $listArticle = $article->SqlGetByStatus(Bdd::GetInstance(), $status);

        return $this->twig->render(
            'Article/admin.html.twig',[
                'articleList' => $listArticle,
                'status' => $status
            ]
        );
    }

    public function show($articleID){
        $SQLArticle = new Article();
        $article = $SQLArticle->SqlGet(Bdd::GetInstance(), $articleID);
        if(!$article->getStatut() == 'Validé') {
            UserController::checkPermission('articles', 'validate');
        }
        return $this->twig->render(
            'Article/article.html.twig',[
                'article' => $article
            ]
        );
    }

    public function search($keyword) {
        $article = new Article();
        $articleList = $article->SqlSearch(Bdd::GetInstance(), ['Titre', 'Description', 'Id', 'Auteur'], $keyword);

        return $this->twig->render(
            'Article/search.html.twig',[
                'articleList' => $articleList,
                'keyword' => $keyword
            ]
        );
    }

    public function add(){
        UserController::checkPermission('articles', 'add');
        $category= new Category();
        $listCategory = $category->SqlGetAll(Bdd::GetInstance());
        if($_POST AND $_SESSION['token'] == $_POST['token']){
            $sqlRepository = null;
            $nomImage = null;
            if(!empty($_FILES['image']['name']) )
            {
                $tabExt = array('jpg','gif','png','jpeg');    // Extensions autorisees
                $extension  = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if(in_array(strtolower($extension),$tabExt))
                {
                    $nomImage = md5(uniqid()) .'.'. $extension;
                    $dateNow = new DateTime();
                    $sqlRepository = $dateNow->format('Y/m');
                    $repository = './uploads/images/'.$dateNow->format('Y/m');
                    if(!is_dir($repository)){
                        mkdir($repository,0777,true);
                    }
                    move_uploaded_file($_FILES['image']['tmp_name'], $repository.'/'.$nomImage);
                }
            }
            $article = new Article();
            $article->setTitre($_POST['Titre'])
                ->setDescription($_POST['Description'])
                ->setAuteur($_POST['Auteur'])
                ->setDateAjout($_POST['DateAjout'])
                ->setImageRepository($sqlRepository)
                ->setImageFileName($nomImage)
                ->setStatut($_POST['statut'])
                ->setCategorie($_POST['categorie'])
            ;
            $article->SqlAdd(BDD::getInstance());
            header('Location:/Article');
        }else{
            // Génération d'un TOKEN
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;
            return $this->twig->render('Article/add.html.twig',
                [
                    'token' => $token,
                    'categories' => $listCategory
                ]);
        }
    }

    public function update($articleID){
        UserController::checkPermission('articles', 'update');
        $articleSQL = new Article();
        $article = $articleSQL->SqlGet(BDD::getInstance(),$articleID);
        $category= new Category();
        $listCategory = $category->SqlGetAll(Bdd::GetInstance());
        if($_POST AND $_SESSION['token'] == $_POST['token']){
            $sqlRepository = null;
            $nomImage = null;
            if(!empty($_FILES['image']['name']) )
            {
                $tabExt = array('jpg','gif','png','jpeg');    // Extensions autorisees
                $extension  = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if(in_array(strtolower($extension),$tabExt))
                {
                    $nomImage = md5(uniqid()) .'.'. $extension;
                    $dateNow = new DateTime();
                    $sqlRepository = $dateNow->format('Y/m');
                    $repository = './uploads/images/'.$dateNow->format('Y/m');
                    if(!is_dir($repository)){
                        mkdir($repository,0777,true);
                    }
                    move_uploaded_file($_FILES['image']['tmp_name'], $repository.'/'.$nomImage);
                    // suppression ancienne image si existante

                    if($_POST['imageAncienne'] != '/'){
                        unlink('./uploads/images/'.$_POST['imageAncienne']);
                    }
                }
            }

            $article->setTitre($_POST['Titre'])
                ->setDescription($_POST['Description'])
                ->setAuteur($_POST['Auteur'])
                ->setDateAjout($_POST['DateAjout'])
                ->setImageRepository($sqlRepository)
                ->setImageFileName($nomImage)
                ->setCategorie($_POST['categorie'])
            ;

            $article->SqlUpdate(BDD::getInstance());
            header('Location:/');
        }else {
            // Génération d'un TOKEN
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;
            return $this->twig->render('Article/update.html.twig', [
                'article' => $article,
                'categories' => $listCategory,
                'token' => $token
            ]);
        }
    }

    public function updateStatus($articleID, $statusID) {
        UserController::checkPermission('articles', 'validate');
        $articleSQL = new Article();
        $article = $articleSQL->SqlGet(BDD::getInstance(),$articleID);

        $article->setStatut($statusID);

        $article->SqlUpdateStatus(BDD::getInstance());
        header('Location:/Admin/2');
    }

    public function Delete($articleID){
        UserController::checkPermission('articles', 'delete');
        $articleSQL = new Article();
        $article = $articleSQL->SqlGet(BDD::getInstance(),$articleID);
        $article->SqlDelete(BDD::getInstance(),$articleID);
        if($article->getImageFileName() != ''){
            unlink('./uploads/images/'.$article->getImageRepository().'/'.$article->getImageFileName());
        }

        header('Location:/');
    }

    public function fixtures(){
        UserController::checkPermission('articles', 'delete');
        $arrayAuteur = array('Fabien', 'Brice', 'Bruno', 'Jean-Pierre', 'Benoit', 'Emmanuel', 'Sylvie', 'Marion');
        $arrayTitre = array('PHP en force', 'React JS une valeur montante', 'C# toujours au top', 'Java en légère baisse'
        , 'Les entreprises qui recrutent', 'Les formations à ne pas rater', 'Les langages populaires en 2020', 'L\'année du Javascript');
        $dateajout = new DateTime();
        $article = new Article();
        $article->SqlTruncate(BDD::getInstance());
        for($i = 1;$i <=200; $i++){
            shuffle($arrayAuteur);
            shuffle($arrayTitre);

            $dateajout->modify('+'.$i.' day');

            $article->setTitre($arrayTitre[0])
                ->setDescription('On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même. L\'avantage du Lorem Ipsum sur un texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour \'Lorem Ipsum\' vous conduira vers de nombreux sites qui n\'en sont encore qu\'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d\'y rajouter de petits clins d\'oeil, voire des phrases embarassantes).')
                ->setDateAjout($dateajout->format('Y-m-d'))
                ->setAuteur($arrayAuteur[0])
                ->setCategorie(rand(1,5))
                ->setStatut(rand(1,3));

            $article->SqlAdd(BDD::getInstance());
        }
        //header('Location:/Article');
    }


    public function Write(){

        $article = new Article();
        $listArticle = $article->SqlGetAll(Bdd::GetInstance());

        $file = 'article.json';
        if(!is_dir('./uploads/file/')){

            mkdir('./uploads/file/', 0777, true);
        }
        file_put_contents('./uploads/file/'.$file, json_encode($listArticle));

        header('location:/Article/');
    }

    public function Read(){
        $file='article.json';
        $datas = file_get_contents('./uploads/file/'.$file);
        $contenu = json_decode($datas);

        return $this->twig->render('Article/readfile.html.twig', [
            'fileData' => $contenu
        ]);
    }

    public function WriteOne($idArticle){
        $article = new Article();
        $articleData = $article->SqlGet(Bdd::GetInstance(), $idArticle);

        $file = 'article_'.$idArticle.'.json';
        if(!is_dir('./uploads/file/')){
            mkdir('./uploads/file/', 0777, true);
        }
        file_put_contents('./uploads/file/'.$file, json_encode($articleData));

        header('location:/Article/');
    }
}
