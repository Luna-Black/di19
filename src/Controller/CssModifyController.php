<?php
namespace src\Controller;

use src\Model\Bdd;

    class CssModifyController extends AbstractController{


       public function openFile(){
            $fichiercss = fopen('style.css', 'c+');
            $read = fread($fichiercss, filesize('style.css'));

           return $this->twig->render('CSS/CssModify.html.twig',[
            'fichiercss'=>$read
            ]);
            var_dump($fichiercss);
            var_dump($read);

        }

        public function writeFile($fichiercss){
           if(ISSET($_POST['css'])){
               $write = fwrite($fichiercss, 'css');
           }
        }

        public function saveFile($fichiercss){
            fclose($fichiercss);
        }


    }
?>