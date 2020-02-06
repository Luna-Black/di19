<?php
namespace src\Controller;

use src\Model\Bdd;
use src\Model\User;

class UserController extends  AbstractController {

    public function listAll() {
        $user = new User();
        $usersList = $user->SqlGetAll(Bdd::GetInstance());

        //Lancer la vue TWIG
        return $this->twig->render(
            'User/users.html.twig',[
                'usersList' => $usersList
            ]
        );
    }

    public function updateRole($email) {
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
        $user = $SQLUser->SqlGet(Bdd::GetInstance(), $_POST['email']);
        var_dump($_POST);
        var_dump($user);
        if($_POST['password'] == $user->getMdp() and $_POST['email'] != ''){
            var_dump('test');
            unset($_SESSION['errorlogin']);
            $_SESSION['login'] = array(
                'Pseudo' => $user->getPseudo(),
                'Email' => $user->getEmail(),
                'Role' => $user->getRole()
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

    public static function checkRoles(array $testedRoles){
        if(isset($_SESSION['login'])){
            if(!in_array($_SESSION['login']['role'], $testedRoles)){
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
            $user->setRole($_POST['role']);
            $user->setValide($_POST['valide']);

            var_dump($user->SqlAdd(Bdd::GetInstance()));




        }
       header('Location:/');

    }

    public function showSignUp() {
        return $this->twig->render('User/signup.html.twig');
    }

    public function update() {

    }

}