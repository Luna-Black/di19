<?php
namespace src\Controller;

use src\Model\Bdd;

    class CssModifyController extends AbstractController{


       public function openFile(){

           $monfichier = fopen('style.css', 'w');
           fputs($monfichier, $_POST['css']);
           fclose($monfichier);

           return $this->twig->render('CSS/CssModify.html.twig',[
            'fichiercss'=>$read
            ]);
        }




    }
?>