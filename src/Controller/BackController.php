<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pack;
use App\Entity\Reservation;
use App\Form\ModifPackType;
use App\Form\PackType;
use App\Form\ReservationType;
use App\Repository\PackRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class BackController extends AbstractController
{

    // -------------------------------------- BACK-OFFICE -------------------------------------- //


    /**
     * @Route("/backoffice", name="backoffice")
     */
    public function backoffice(ReservationRepository $repository, UserRepository $userRepository, PackRepository $packRepository)
    {
        $reservations=$repository->findBy(array(), array('date'=>'ASC'), 5, null);

        $top_clients=$userRepository->findBy(array(), array('resa_eff'=>'DESC'), 3, null);
//dd($top_clients);
        $top_packs=$packRepository->findBy(array(), array('nbResa'=>'DESC'), 3, null);
        return $this->render("/back/backoffice.html.twig", [
            'reservations'=>$reservations,
            'clients'=>$top_clients,
            'packs'=>$top_packs
        ]);
    }

    /**
     * @Route("/gestion_reservations", name="gestion_reservations")
     */
    public function gestion_reservations(ReservationRepository $repository)
    {
        $reservations=$repository->findAll();
        return $this->render('back/gestion_reservations.html.twig',[
            'reservations'=>$reservations
        ]);
    }

    /**
     * @Route("/gestion_packs", name="gestion_packs")
     */
    public function gestion_packs(PackRepository $repository)
    {
        $packs=$repository->findAll();
        return $this->render('back/gestion_packs.html.twig',[
            'packs'=>$packs
        ]);
    }

    /**
     * @Route("/gestion_clients", name="gestion_clients")
     */
    public function gestion_clients(UserRepository $repository, ReservationRepository $reservationRepository)
    {
        $users=$repository->findAll();
        $reservations=$reservationRepository->findAll();

        return $this->render('back/gestion_clients.html.twig',[
            'users'=>$users,
            'reservations'=>$reservations
        ]);
    }
// -----        permet l'affichage des réservations effectuées par client dans back-office        ----- //
    /**
     * @Route("/resa_client/{id}", name="resa_client")
     */
    public function resa_client( $id, User $user, ReservationRepository $reservationRepository)
    {
        $resa_client=$reservationRepository->findResaUser($id);
        return $this->render('back/resa_clients.html.twig', [
//            'pack'=>$pack,
            'reservations'=>$resa_client
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
        return $this->render('back/ajout_pack.html.twig', [
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

        return $this->render("/back/modif_pack.html.twig", [
            'formModifPack'=>$form->createView(),
            'pack'=>$pack
        ]);

}

            // -------------------- SUPPRESSION PACK --------------------//

    /**
     * @Route("/suppr_pack/{id}", name="suppr_pack")
     */
    public function suppr_pack($id, Pack $pack, EntityManagerInterface $manager, ReservationRepository $reservationRepository)
    {
        $resa_pack=$reservationRepository->findResaPack($id);
        if ($resa_pack):
            $this->addFlash('danger', 'Ce pack est actuellement réservé. Sa suppression n\'est pas possible avant l\'annulation de ces réservations');
        else:

        $manager->remove($pack);
        $manager->flush();
        $this->addFlash('success', "Le pack a bien été supprimé.");
        endif;

        return $this->redirectToRoute('gestion_packs');
    }


//  ------------------------------------- RESERVATION ----------------------------------------------- //



    // ----------------------- MODIFICATION RESERVATION ------------------------//
    /**
    * @Route("/modif_reservation/{id}", name="modif_reservation")
    */
    public function modif_reservation(EntityManagerInterface $manager,Request $request, ReservationRepository $reservationRepository, PackRepository $packRepository, UserRepository $userRepository, Reservation $reservation=null, $id=null)
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $reservation->setUser($this->getUser());
        $packs=$packRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()):  // formulaire post ajout_reservation
//            dd($form);
            $d=new \DateTime($request->request->get('reservation')['date']);
            $p=$request->request->get('reservation')['pack'];
            $resa_bdd=$reservationRepository->findBy(array('date'=>$d, 'pack'=>$p)); // vérif bdd si résa pack et jour

            if (count($resa_bdd)==0):
                $pack=$packRepository->find($request->request->get('reservation')['pack']);
                $NbResa=$pack->getNbResa();
                $pack->setNbResa($NbResa+=1);

//          --------    A chaque réservation, on incrémente $resa_eff du client pour fonction top_client   ----
                $user=$this->getUser();
                $resa_eff=$user->getResaEff();
                $user->setResaEff($resa_eff+=1);


                $manager->persist($reservation);
//          $manager->persist(($pack));
                $manager->flush();

                $this->addFlash("success", "Votre réservation a bien été modifiée");
                return $this->redirectToRoute("home");

                else:
                $this->addFlash('danger', "Le pack n'est pas disponible à cette date");
                    return $this->render('back/modif_reservation.html.twig',[
                        'formResa'=>$form->createView(),
                        'packs'=>$packs,

                    ]);
            endif;
        endif;

        return $this->render('back/modif_reservation.html.twig',[
            'formResa'=>$form->createView(),
            'packs'=>$packs,

        ]);

}


    // ----------------------- SUPPRESSION RESERVATION ------------------------//
    /**
     * @Route("/suppr_reservation/{id}", name="suppr_reservation")
     */
    public function suppr_reservation(Reservation $reservation, EntityManagerInterface $manager, $id)
    {
        $manager->remove($reservation);
        $manager->flush();

        $this->addFlash('success', "La réservation a bien été supprimée.");
        return $this->redirectToRoute('gestion_reservations');
    }

}
