<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{


    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('front/home.html.twig');

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


    // -------------------------------------- PAIEMENT  -------------------------------------- //

    /**
     * @Route("/paiement", name="paiement")
     */
    public function paiement()
    {

        return $this->render('front/pagePaiement.html.twig');
    }

    /**
     * @Route("/remerciement", name="remerciement")
     */
    public function merci()
    {

        return $this->render('front/remerciement.html.twig');
    }


    // -------------------- A METTRE FRONTCONTROLLER --------------------//
            // une fois qu'il y aura une page qui montre tous les packs
    /**
     * @Route("/detail_pack/{id}", name="detail_pack")
     */
    public function detail_pack(Pack $pack, $id)
    {
        return $this->render('back/detail_pack.html.twig', [
            'pack'=>$pack
        ]);
    }



}


