<?php
namespace src\Controller;
use src\Model\Category;
use src\Model\Bdd;

class CategoryController extends AbstractController {

    public function add() {
        UserController::roleNeed('Administrateur');
        if($_POST){
            $category = new Category();
            $category->setNom($_POST['Nom']);

            $category->SqlAdd(Bdd::GetInstance());
            header('Location:/Category');
        }

    }

    public function delete($categoryID) {
        $categorySQL = new Category();
        $category = $categorySQL->SqlGet(Bdd::GetInstance(),$categoryID);
        $category->SqlDelete(Bdd::GetInstance(),$categoryID);

        header('location/Admin/Category');
    }


    public function update($categoryID) {
        $categorySQL = new Category();
        $category = $categorySQL->SqlGet(Bdd::GetInstance(),$categoryID);
        if($_POST){
            $category->setNom($_POST['Nom']);
            $category->SqlUpdate(Bdd::GetInstance());
        }
        return $this->twig->render('Article/catupdate.html.twig',[
            'category'=>$category
        ]);
    }

    public function listAll() {
        $category = new Category();
        $listCategory = $category->SqlGetAll(Bdd::GetInstance());

        return $this->twig->render('Article/category.html.twig',[
                   'categorie'=>$listCategory
        ]);

    }
}