<?php
namespace src\Controller;

use src\Model\Bdd;

    class CssModifyController extends AbstractController{


       public function openFile(){
            $fichiercss = fopen('style.css', 'c+');

            $this->twig->render('CSS/CssModify.html.twig',[
            'fichiercss'=>$fichiercss
            ]);

        }

        public function saveFile($fichiercss){
            fclose($fichiercss);
        }


    }
?>