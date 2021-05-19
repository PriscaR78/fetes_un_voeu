<?php
namespace App\Controller;

use Swift_Mailer;
use Swift_Image;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController{     

       /**
     * @Route("/mail", name="mail")
     */
    public function send_email(request $request)
    {

        if (!empty($request->request)):
           // dd($request->request->get('email'));
        $transporter = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername('priska.roberts@gmail.com')
            ->setPassword('@Elena2008');

        $mailer = new Swift_Mailer($transporter);
$mess=$request->request->get('message');
$nom=$request->request->get('surname');
$prenom=$request->request->get('name');
$motif=$request->request->get('need');
   
        $message = (new Swift_Message("$motif"))
            ->setFrom($request->request->get('email'))
            ->setTo(['priska.roberts@gmail.com'=> 'Prisca']);
            $cid = $message->embed(Swift_Image::fromPath('images/imagesUpload/logo/logo_contact.png'));
             $message->setBody(

                $this->renderView('email/mailer.html.twig',[
                    'message'=>$mess,
                       'nom'=>$nom,
                    'prenom'=>$prenom,
                    'motif'=>$motif,
                    'email'=>$request->request->get('email'),
                    'cid'=>$cid
                ]),
                'text/html'
            );


// Send the message
        $result = $mailer->send($message);


        $this->addFlash('success', 'email envoyé');
        return $this->redirectToRoute('home');
        endif;
    }
    /**
     * @Route("/sendform", name="send_form")
     */
    public function form_email()
    {
        return $this->render('email/mail.html.twig');
    }
}