<?php
namespace src\Controller;

use src\Model\Bdd;
use src\Model\User;

class UserController extends  AbstractController {

    public function loginForm(){
        var_dump($_SESSION['errorlogin']);
        return $this->twig->render('User/login.html.twig');
    }

    public function loginCheck(){

        if(!filter_var(
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
        }



    }

    public static function roleNeed($roleATester){
        if(isset($_SESSION['login'])){
            if(!in_array($roleATester,$_SESSION['login']['roles'])){
                $_SESSION['errorlogin'] = "Manque le role : ".$roleATester;
                header('Location:/Contact');
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
            $user->setPassword($_POST['password']);
            $user->setEmail($_POST['email']);
            $user->setRole($_POST['role']);
            $user->setValide($_POST['valide']);

            $user->SqlAdd(Bdd::GetInstance());

        }
        header('Location:/');

    }

    public function showSignUp() {
        return $this->twig->render('User/signup.html.twig');
    }

    public function listAll() {

    }

    public function update() {

    }

}