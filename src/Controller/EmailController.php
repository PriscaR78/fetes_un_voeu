<?php
namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController{     

        /**
        * @Route("/mail", name="mail")
        */
        public function send_email(Request $request)
        {
        
        if(!empty($request->request)):
        $transporter = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername('priska.roberts@gmail.com')
            ->setPassword('@Elena2008');

        $mailer = new Swift_Mailer($transporter);

        $mess=$request->request->get('message');
        $nom=$request->request->get('surname');
        $prenom=$request->reqiest->get('name');
        $motif=$request->request->get('need');
        $image='data:image/jpeg;base64,'.base64_encode(file_get_contents('fleche.png'));    


        $message = (new Swift_Message("$motif"))
            ->setFrom($request->request->get('email'))
            ->setTo(['priska.roberts@gmail.com'=> 'Priska']);
       // $image=base64_encode(file_get_contents('fleche.png'));
       $cid = $message->embed(Swift_Image::fromPatch('fleche.png'));
             $message->setBody(
             $this->renderView('email/mail.html.twig',[
              'message'=>$mess
            ]),
            'text/html'
        );

// Send the message
        $result = $mailer->send($message);

        $this->addFlash('success', 'Votre message est bien envoyé');
        return $this->redirectToRoute('home');
        endif;

    }

    /**
     * @Route("/sendform", name="send_form")
     */
    public function form_email()
    {
        return $this->render('Email/mail.html.twig');
    }
}