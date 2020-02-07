<?php
namespace src\Controller;

use src\Model\Bdd;
use src\Model\User;

class UserController extends  AbstractController {

    public function listAll() {
        UserController::checkPermission('users', 'updatePermissions');
        $user = new User();
        $usersList = $user->SqlGetAll(Bdd::GetInstance());

        //Lancer la vue TWIG
        return $this->twig->render(
            'User/users.html.twig',[
                'usersList' => $usersList
            ]
        );
    }

    public function showUserPage($id) {
        if(isset($_SESSION['login'])) {
            $SQLUser = new User();
            $user = $SQLUser->SqlGet(Bdd::GetInstance(), $id);
            return $this->twig->render('User/userpage.html.twig',[
                'user' => $user,
                    'token' => $_SESSION['login']['TokenApi']
                ]
            );
        }else{
            $_SESSION['errorlogin'] = 'Veuillez vous connectez.';
            header('Location:/Login');
        }
    }

    public function updateRole($email) {
        UserController::checkPermission('users', 'updatePermissions');
        $SQLUser = new User();
        $user = $SQLUser->SqlGet(Bdd::GetInstance(), $email);

        if($_POST) {
            $user->setRole($_POST['role']);
            $user->SqlUpdateRole(Bdd::GetInstance());
        }
        header('Location:/Admin/Users');
    }

    public function loginForm(){
        return $this->twig->render('User/login.html.twig');
    }

    public function loginCheck(){
        $SQLUser = new User();
        $user = $SQLUser->SqlGet(Bdd::GetInstance(), $_POST['username']);
        if(password_verify($_POST['password'], $user->getMdp()) and $_POST['username'] != ''){
            unset($_SESSION['errorlogin']);
            $_SESSION['login'] = array(
                'Pseudo' => $user->getPseudo(),
                'Email' => $user->getEmail(),
                'Role' => $user->getRole(),
                'Permissions' => $user->getPermissions(),
                'TokenApi' => $user->getTokenApi()
            );
            header('Location:/');
        }else{
            $_SESSION['errorlogin'] = 'Erreur Authent.';
            header('Location:/Login');
        }
    }


        /*if(!filter_var(
            $_POST['password'],
            FILTER_VALIDATE_REGEXP,
            array(
                "options" => array("regexp"=>"/[a-zA-Z]{3,}/")
            )
        )){
            $_SESSION['errorlogin'] = "Mpd mini 3 caractères";
            header('Location:/Login');
            return;
        }

        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            $_SESSION['errorlogin'] = "Mail invalide";
            header('Location:/Login');
            return;
        }

        if($_POST["email"]=="admin@admin.com"
            AND $_POST["password"] == "password"
        ){

            $_SESSION['login'] = array(
                'Nom' => 'Administrateur'
            ,'Prénom' => 'Sylvain'
            ,'roles' => array('admin', 'redacteur')
            );
            header('Location:/');
        }else{
            $_SESSION['errorlogin'] = "Erreur Authent.";
            header('Location:/Login');
        }*/


    public function updatePermissions($username) {
        UserController::checkPermission('users', 'updatePermissions');
        $SQLUser = new User();
        $user = $SQLUser->SqlGet(Bdd::GetInstance(), $username);

        if($_POST){
            $permissionsDict = array(
                "articles" => array(
                    "add" => in_array('add', $_POST['articles']),
                    "update" => in_array('update', $_POST['articles']),
                    "delete" => in_array('delete', $_POST['articles']),
                    "validate" => in_array('validate', $_POST['articles'])
                ),
                "categories" => array(
                    "add" => in_array('add', $_POST['categories']),
                    "update" => in_array('update', $_POST['categories']),
                    "delete" => in_array('delete', $_POST['categories'])
                ),
                "users" => array(
                    "updatePermissions" => in_array('updatePermissions', $_POST['users'])
                )
            );
            $permissionsJson = json_encode($permissionsDict);
            $user->setPermissions($permissionsJson);
            $user->SqlUpdatePermissions(Bdd::GetInstance());
            //header('Location:/Admin/Users');
        }
        $permissions = json_decode($user->getPermissions());
        return $this->twig->render('User/permissions.html.twig', [
            'user' => $user,
            'permissions' => $permissions
        ]);
    }

    public static function checkPermission($PermissionCat, $testedPermission){
        if(isset($_SESSION['login'])){
            $permissions = json_decode($_SESSION['login']['Permissions'], true);
            if(!$permissions[$PermissionCat][$testedPermission]){
                $_SESSION['errorlogin'] = "Vous n'avez pas les droits";
                header('Location:/Login');
            }
        }else{
            $_SESSION['errorlogin'] = "Veuillez vous identifier";
            header('Location:/Login');
        }
    }

    public function logout(){
        unset($_SESSION['login']);
        unset($_SESSION['errorlogin']);

        header('Location:/');
    }

    public function signup(){
        if($_POST){
            $user = new User();
            $user->setPseudo($_POST['pseudo']);
            $user->setMdp($_POST['password']);
            $user->setEmail($_POST['email']);
            }

        var_dump($user->SqlAdd(Bdd::GetInstance()));

        return $this->twig->render('User/compte.html.twig');

       }


    public function showSignUp() {
        return $this->twig->render('User/signup.html.twig');
    }
}