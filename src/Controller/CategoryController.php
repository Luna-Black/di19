<?php
namespace src\Controller;
use src\Model\Category;
use src\Model\Bdd;

class CategoryController extends AbstractController {

    public function add() {
        //UserController::checkRoles(['Administrateur']);
        if($_POST){
            $category = new Category();
            $category->setNom($_POST['Nom']);

            $category->SqlAdd(Bdd::GetInstance());

        }
        header('Location:/Admin/Categories');

    }

    public function delete($categoryID) {
       // UserController::checkRoles(['Administrateur']);
        $categorySQL = new Category();
        $category = $categorySQL->SqlGet(Bdd::GetInstance(),$categoryID);
        $category->SqlDelete(Bdd::GetInstance(),$categoryID);

        header('location:/Admin/Categories');
    }


    public function update($categoryID) {
       // UserController::checkRoles(['Administrateur']);
        $categorySQL = new Category();
        $category = $categorySQL->SqlGet(Bdd::GetInstance(),$categoryID);
        if($_POST){
            $category->setNom($_POST['Nom']);
            $category->SqlUpdate(Bdd::GetInstance());
            var_dump($category);

            header('location:/Admin/Categories');
        }
        return $this->twig->render('Article/catupdate.html.twig',[
            'category'=>$category
        ]);

    }

    public function listAll() {
       // UserController::checkRoles(['Administrateur']);
        $category = new Category();
        $listCategory = $category->SqlGetAll(Bdd::GetInstance());

        return $this->twig->render('Article/category.html.twig',[
                   'categories'=>$listCategory
        ]);

    }
}