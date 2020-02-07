<?php
namespace src\Controller;

use src\Model\Article;
use src\Model\Bdd;

class ContactController extends AbstractController{
    private $mailer;
    private $transport;

    public function __construct()
    {
        parent::__construct();
        $this->transport = (new \Swift_SmtpTransport('smtp.mailtrap.io', 465))
            ->setUsername('82f90a87eb5933')
            ->setPassword('cfd1ab8dcbd215');
        $this->mailer = new \Swift_Mailer($this->transport);

    }

    public function showForm(){
        return $this->twig->render('Contact/form.html.twig');
    }

    public function sendMail(){
        $mail = (new \Swift_Message('Contact depuis le formulaire'))
            ->setFrom([$_POST["email"] => $_POST["nom"]])
            ->setTo('contact@monsite.fr')
            ->setBody(
                $this->twig->render('Contact/mail.html.twig',
                    [
                        'message' => $_POST["content"]
                    ])
                ,'text/html'
            );

        $result = $this->mailer->send($mail);

        return $result;
    }

    public function sendMailByArticle($id) {
        $SQLArticle = new Article();
        $article = $SQLArticle->SqlGet(Bdd::GetInstance(), $id);
        $mail = (new \Swift_Message($article->getTitre()))
            ->setFrom([$_POST["email"] => $_POST["name"]])
            ->setTo('contact@monsite.fr')
            ->setBody(
                $this->twig->render('Contact/mailarticle.html.twig',
                    [
                        'message' => $_POST["content"],
                        'article' => $article
                    ])
                ,'text/html'
            );

        $this->mailer->send($mail);
        header('Location:/Article/Show/'.$id);
    }

}
