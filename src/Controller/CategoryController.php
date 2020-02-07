<?php
namespace src\Controller;
use src\Model\Category;
use src\Model\Bdd;

class CategoryController extends AbstractController {

    public function add() {
        UserController::checkPermission('categories', 'add');
        $category= new Category();
        $listCategory = $category->SqlGetAll(Bdd::GetInstance());
        if($_POST AND $_SESSION['token'] == $_POST['token']){
            $category = new Category();
            $category->setNom($_POST['Nom']);

            $category->SqlAdd(Bdd::GetInstance());
            header('Location:/Admin/Categories');
        }else {
            // Génération d'un TOKEN
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;
            return $this->twig->render('Article/category.html.twig',
                [
                    'token' => $token,
                    'categories'=> $listCategory
                ]);

        }

    }


    public function delete($categoryID) {
        UserController::checkPermission('categories', 'delete');
        $categorySQL = new Category();
        $category = $categorySQL->SqlGet(Bdd::GetInstance(),$categoryID);
        $category->SqlDelete(Bdd::GetInstance(),$categoryID);

        header('location:/Admin/Categories');
    }


    public function update($categoryID) {
        UserController::checkPermission('categories', 'update');
        $categorySQL = new Category();
        $category = $categorySQL->SqlGet(Bdd::GetInstance(),$categoryID);
        if($_POST AND $_SESSION['token'] == $_POST['token']){
            $category->setNom($_POST['Nom']);
            $category->SqlUpdate(Bdd::GetInstance());

            header('location:/Admin/Categories');
        }else {
            // Génération d'un TOKEN
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;
            return $this->twig->render('Article/catupdate.html.twig', [
                'category' => $category,
                'token' => $token
            ]);
        }

    }

    public function listAll() {
        UserController::checkPermission('categories', 'add');
        $category = new Category();
        $listCategory = $category->SqlGetAll(Bdd::GetInstance());

        return $this->twig->render('Article/category.html.twig',[
                   'categories'=>$listCategory
        ]);

    }
}