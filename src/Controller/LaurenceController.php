<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ModifPackType;
use App\Form\PackType;
use App\Form\RegistrationType;
use App\Form\ReservationType;
use App\Repository\PackRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LaurenceController extends AbstractController
{
//    /**
//     * @Route("/laurence", name="laurence")
//     */
//    public function index(): Response
//    {
//        return $this->render('laurence/index.html.twig', [
//            'controller_name' => 'LaurenceController',
//        ]);
//    }

    // -------------------------------------- BACK-OFFICE -------------------------------------- //

    /**
     * @Route("/backoffice", name="backoffice")
     */
    public function backoffice()
    {
        return $this->render("/laurence/backoffice.html.twig");
    }

    /**
     * @Route("/gestion_reservations", name="gestion_reservations")
     */
    public function gestion_reservations(ReservationRepository $repository)
    {
        $reservations=$repository->findAll();
        return $this->render('laurence/gestion_reservations.html.twig',[
            'reservations'=>$reservations
        ]);
    }

    /**
     * @Route("/gestion_packs", name="gestion_packs")
     */
    public function gestion_packs(PackRepository $repository)
    {
        $packs=$repository->findAll();
        return $this->render('laurence/gestion_packs.html.twig',[
            'packs'=>$packs
        ]);
    }

    /**
     * @Route("/gestion_clients", name="gestion_clients")
     */
    public function gestion_clients(UserRepository $repository)
    {
        $users=$repository->findAll();
        return $this->render('laurence/gestion_clients.html.twig',[
            'users'=>$users
        ]);
    }


    // -------------------------------------- PACK  -------------------------------------- //
    /**
     * @Route("/ajout_pack", name="ajout_pack")
     */
    public function ajout_pack(Request $request, EntityManagerInterface $manager)
    {
        $pack = new Pack();

        $form=$this->createForm(PackType::class, $pack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):

            $image1File=$form->get('image1')->getData();
            $image2File=$form->get('image2')->getData();
            $image3File=$form->get('image3')->getData();

            if ($image1File):
                $nomImage1=date("YmdHis").uniqid()."-".$image1File->getClientOriginalName();
                try {
                    $image1File->move (
                        $this->getParameter('images_directory'),
                        $nomImage1
                    );
                }
                catch (FileException $e) {
                    $this->redirectToRoute('ajout_pack', [
                        'erreur'=>$e
                    ]);
                }
                $pack->setImage1($nomImage1);
            endif;

            if ($image2File):
                $nomImage2=date("YmdHis").uniqid()."-".$image2File->getClientOriginalName();

                try {
                    $image2File->move (
                        $this->getParameter('images_directory'),
                        $nomImage2
                    );
                }
                catch (FileException $e) {
                    $this->redirectToRoute('ajout_pack', [
                        'erreur'=>$e
                    ]);
                }

                $pack->setImage2($nomImage2);
            endif;

            if ($image3File):
                $nomImage3=date("YmdHis").uniqid()."-".$image3File->getClientOriginalName();

                try {
                    $image3File->move (
                        $this->getParameter('images_directory'),
                        $nomImage3
                    );
                }
                catch (FileException $e) {
                    $this->redirectToRoute('ajout_pack', [
                        'erreur'=>$e
                    ]);
                }

                $pack->setImage3($nomImage3);

            endif;

            $manager->persist($pack);
            $manager->flush();
            $this->addFlash("success", "Le pack a bien été ajouté.");


//        A DECOMMENTER QUAND ROUTE EXISTERA
            return $this->redirectToRoute("gestion_packs");
        endif;


//    VERSION PROVISOIRE
        return $this->render('laurence/ajout_pack.html.twig', [
            'formPack'=>$form->createView()
        ]);

//        VERSION DEFINITIVE
//    return $this->render('back/pack/ajout_pack', [
//        'formPack'=>$form->createView()
//    ]);

    }

    // -------------------- MODIFICATION PACK --------------------//

    /**
     * @Route("/modif_pack/{id}", name="modif_pack")
     */
    public function modif_pack(Pack $pack, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ModifPackType::class, $pack);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()):
            //  -----------  IMAGE1  -----------  //
            $image1File = $form->get('image1File')->getData();
            if ($image1File):
                $nomimage1 = date('YmdHis') . "-" . uniqid() . "-" . $image1File->getClientOriginalName();

                try {
                    $image1File->move(
                        $this->getParameter('images_directory'),
                        $nomimage1
                    );
                }
                catch (FileException $e){

                }
                if(!empty($pack->getImage1())):
                    unlink($this->getParameter('images_directory') . '/' . $pack->getImage1());
                endif;
                $pack->setImage1($nomimage1);
            endif;

            //  -----------  IMAGE2  -----------  //
            $image2File = $form->get('image2File')->getData();
            if ($image2File):
                $nomimage2 = date('YmdHis') . "-" . uniqid() . "-" . $image2File->getClientOriginalName();

                try {
                    $image2File->move(
                        $this->getParameter('images_directory'),
                        $nomimage2
                    );
                }
                catch (FileException $e){

                }
                if(!empty($pack->getImage2())):
                    unlink($this->getParameter('images_directory') . '/' . $pack->getImage2());
                endif;
                $pack->setImage2($nomimage2);
            endif;

            //  -----------  IMAGE3  -----------  //
            $image3File = $form->get('image3File')->getData();
            if ($image3File):
                $nomimage3 = date('YmdHis') . "-" . uniqid() . "-" . $image3File->getClientOriginalName();

                try {
                    $image3File->move(
                        $this->getParameter('images_directory'),
                        $nomimage3
                    );
                }
                catch (FileException $e){

                }
                if(!empty($pack->getImage3())):
                    unlink($this->getParameter('images_directory') . '/' . $pack->getImage3());
                endif;
                $pack->setImage3($nomimage3);
            endif;

            $manager->persist($pack);
            $manager->flush();

            return $this->redirectToRoute("gestion_packs");

        endif;

        return $this->render("/laurence/modif_pack.html.twig", [
            'formModifPack'=>$form->createView()
        ]);

    }

    // -------------------- SUPPRESSION PACK --------------------//

    /**
     * @Route("/suppr_pack/{id}", name="suppr_pack")
     */
    public function suppr_pack(Pack $pack, EntityManagerInterface $manager, $id)
    {
        $manager->remove($pack);
        $manager->flush();

        $this->addFlash('success', "Le pack a bien été supprimé.");
        return $this->redirectToRoute('gestion_packs');
    }







    // -------------------- RESERVATION --------------------//

    /**
     * @Route("ajout_resa", name="ajout_resa")
     */
    public function ajout_resa(Request $request, EntityManagerInterface $manager)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $reservation->setUser($this->getUser());


        if ($form->isSubmitted() && $form->isValid()):

            $manager->persist($reservation);
            $manager->flush();

            $this->addFlash("success", "La réservation a bien été enregistrée");

            // provisoire à modifier avec vraie route
            return $this->redirectToRoute("home");

        endif;

        return $this->render('laurence/ajout_reservation.html.twig',[
            'formResa'=>$form->createView(),
        ]);




    }


    public function verif_dispo(Pack $pack, Request $request, ReservationRepository $reservationRepository)
    {
//         Dans le repository ? reservation where date_bdd = date_formulaire_utilisateur
//        $form // il faut récupérer le nom du pack et le réassocier à son id
//        $now = date('Y-m-d',time());
//        $nowstr=strtotime($now); // temps en secondes depuis 1/1/1970
//        $resa=$reservationRepository->find();
    }






    // -------------------- A METTRE FRONTCONTROLLER --------------------//
    // A REPRENDRE NE MARCHE PAS PROB ROUTE VOIR AUSSU gestion_packs.html.twig et detail_pack.html.twig
    /**
     * @Route("/detail_pack/{id}", name="detail_pack")
     */
    public function detail_pack(Pack $pack)
    {
        return $this->render('laurence/detail_pack.html.twig', [
            'pack'=>$pack
        ]);
    }










}

