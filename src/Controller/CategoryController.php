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
        //$category = $categorySQL->

    }

    public function update() {

    }

    public function listAll() {

    }
}