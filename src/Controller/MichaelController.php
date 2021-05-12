<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Entity\Reservation;
use App\Form\PackType;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MichaelController extends AbstractController
{
    /**
     * @Route("/michael", name="michael")
     */
    public function index(): Response
    {
        return $this->render('michael/index.html.twig', [
            'controller_name' => 'MichaelController',
        ]);
    }



//    /**
//     * @Route("/laurence", name="laurence")
//     */
//    public function index(): Response
//    {
//        return $this->render('laurence/index.html.twig', [
//            'controller_name' => 'LaurenceController',
//        ]);
//    }
    // -------------------- PACK -------------------- //
    /**
     * @Route("/ajout_pack", name="ajout_pack")
     */
    public function ajout_pack(Request $request, EntityManagerInterface $manager)
    {
        $pack = new Pack();

        $form = $this->createForm(PackType::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):

            $image1File = $form->get('image1')->getData();
            $image2File = $form->get('image2')->getData();
            $image3File = $form->get('image3')->getData();

            if ($image1File):
                $nomImage1 = date("YmdHis") . uniqid() . "-" . $image1File->getClientOriginalName();
                try {
                    $image1File->move(
                        $this->getParameter('images_directory'),
                        $nomImage1
                    );
                } catch (FileException $e) {
                    $this->redirectToRoute('ajout_pack', [
                        'erreur' => $e
                    ]);
                }
                $pack->setImage1($nomImage1);
            endif;

            if ($image2File):
                $nomImage2 = date("YmdHis") . uniqid() . "-" . $image2File->getClientOriginalName();

                try {
                    $image2File->move(
                        $this->getParameter('images_directory'),
                        $nomImage2
                    );
                } catch (FileException $e) {
                    $this->redirectToRoute('ajout_pack', [
                        'erreur' => $e
                    ]);
                }

                $pack->setImage2($nomImage2);
            endif;

            if ($image3File):
                $nomImage3 = date("YmdHis") . uniqid() . "-" . $image3File->getClientOriginalName();

                try {
                    $image3File->move(
                        $this->getParameter('images_directory'),
                        $nomImage3
                    );
                } catch (FileException $e) {
                    $this->redirectToRoute('ajout_pack', [
                        'erreur' => $e
                    ]);
                }

                $pack->setImage3($nomImage3);

            endif;

            $manager->persist($pack);
            $manager->flush();
            $this->addFlash("success", "Le pack a bien été ajouté.");


//        A DECOMMENTER QUAND ROUTE EXISTERA
//        return $this->redirectToRoute("gestion_pack");
        endif;


//    VERSION PROVISOIRE
        return $this->render('laurence/ajout_pack.html.twig', [
            'formPack' => $form->createView()
        ]);

//        VERSION DEFINITIVE
//    return $this->render('back/pack/ajout_pack', [
//        'formPack'=>$form->createView()
//    ]);

    }














    // -------------------- RESERVATION --------------------//

    /**
     * @Route("/ajout_reservation/{id}", name="ajout_reservation")
     */
    public function ajout_reservation(Request $request, EntityManagerInterface $manager)
    {

        $reservation = new Reservation();
//        $user = new User();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()):

            $manager->persist($reservation);
            $manager->flush();

            $this->addFlash("success", "La réservation a bien été enregistrée");


            // provisoire à modifier avec vraie route
            return $this->redirectToRoute("home");

        endif;

        return $this->render('laurence/ajout_reservation.html.twig', [
            'formResa' => $form->createView(),
        ]);


    }


    public function verif_dispo(Pack $pack, Request $request, ReservationRepository $reservationRepository)
    {
//        $form // il faut récupérer le nom du pack et le réassocier à son id
        $now = date('Y-m-d', time());
        $nowstr = strtotime($now); // temps en secondes depuis 1/1/1970
//        $resa=$reservationRepository->find();
    }





}