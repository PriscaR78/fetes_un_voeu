<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{


    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $informations=[
            'fonction'=>'doudou',
            'faiblesse'=>'cookies'
        ];

        return $this->render('front/home.html.twig', [
            'prenom'=>'Marcel',
            'age'=>125,
            'informations'=>$informations
        ]);

       }

       /**
        * @Route("/profil", name="profil")
        */
    public function profil()
    {
        return $this->render('front/profil.html.twig');
    }
}
