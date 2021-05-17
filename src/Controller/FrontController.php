<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
            'faiblesse'=>'cookies',
            'today'=>date('Y-m-d')
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
    public function profil(ReservationRepository $reservationRepository, UserRepository $userRepository)
    {
        $resa_client=$reservationRepository->findBy(array('user'=>$this->getUser()));


        return $this->render('front/profil.html.twig', [
            'reservations'=>$resa_client
        ]);
    }


}
