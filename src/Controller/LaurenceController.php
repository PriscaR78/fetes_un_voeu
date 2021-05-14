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
    public function backoffice(ReservationRepository $repository)
    {
        $reservations=$repository->findBy(array(), array('date'=>'ASC'), 5, null);

        return $this->render("/laurence/backoffice.html.twig", [
            'reservations'=>$reservations
        ]);
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
    public function gestion_clients(UserRepository $repository, ReservationRepository $reservationRepository)
    {
        $users=$repository->findAll();
        $reservations=$reservationRepository->findAll();

        return $this->render('laurence/gestion_clients.html.twig',[
            'users'=>$users,
            'reservations'=>$reservations
        ]);
    }

    /**
     * @Route("/resa_client/{id}", name="resa_client")
     */
    public function resa_client( $id, User $user, ReservationRepository $reservationRepository)
    {
        $resa_client=$reservationRepository->findResaUser($id);
        return $this->render('laurence/resa_clients.html.twig', [
//            'pack'=>$pack,
            'reservations'=>$resa_client
        ]);
    }

    /**
     * @Route("/top_pack", name="top_pack")
     */
    public function top_pack(ReservationRepository $reservationRepository, PackRepository $packRepository)
    {
//        $pack=$packRepository->findAll();
//        $top_packs=$reservationRepository->findTopPack($pack);
        $top_packs=$reservationRepository->findBy(array(), array('pack'=>'ASC'), 5, null);
        dd($top_packs);
        return $this->render("/laurence/index.html.twig", [
            'top_packs'=>$top_packs
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


    // ----------------------- RESERVATION ------------------------//

    /**
     * @Route("ajout_resa", name="ajout_resa")
     */
    public function ajout_resa(Request $request, EntityManagerInterface $manager, ReservationRepository $reservationRepository)
    {

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $reservation->setUser($this->getUser());


        if ($form->isSubmitted() && $form->isValid()):
//            $resa_bdd=$reservationRepository->findByPackDate($request->query->get('pack'), $request->query->get('date'));
//            if ($resa_bdd == count(null)):

            $manager->persist($reservation);
            $manager->flush();

            $this->addFlash("success", "La réservation a bien été enregistrée");

            // provisoire à modifier avec vraie route
            return $this->redirectToRoute("home");
//                endif;
        endif;

//        return $this->render('laurence/ajout_reservation.html.twig',[
        return $this->render('laurence/reservation.html.twig',[
            'formResa'=>$form->createView(),
        ]);




    }

    // ----------------------- MODIF RESERVATION ------------------------//




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


    // ----------------------- VERIFICATION DISPONIBILITE ------------------------//

    /**
     * @Route("/verif_dispo", name="verif_dispo")
     */
    public function verif_dispo(Request $request, ReservationRepository $reservationRepository, PackRepository $packRepository)
    {
        $reservations=$reservationRepository->findAll();
        $packs=$packRepository->findAll();

        // ------------ CONTROLE FORMULAIRE ------------ //

        $resa_min= date('Y-m-d', time());
        $resa_max = date('Y-m-d',time() + (365 * 24 * 60 * 60 ))   ;



        // ------------ CONTROLES RECEPTION FORMULAIRE ------------ //

        if(!empty($request->query->all())):
            $request->query->all(); // va chercher le formulaire get
//          dump($request);
            $date_debut=$request->query->get('date_debut');
            $date_fin=$request->query->get('date_fin');

            $timstamp_debut = strtotime($date_debut);
            $timstamp_fin = strtotime($date_fin);
//          dump($date_debut);
//          dump($timstamp_debut);

            if ($timstamp_fin < $timstamp_debut):
//              dump($timstamp_fin);
                $this->addFlash('danger', "Les dates renseignées ne sont pas cohérentes");
            endif;
//        $this->redirectToRoute('verif_dispo');


            // ------------ ENVOI DONNEES FORMULAIRE VERS REPOSITORY ------------ //

            $date_debut= new \DateTime($request->query->get('date_debut'));
            $date_fin= new \DateTime($request->query->get('date_fin'));

            $packs=$packRepository->findAll();
            $reservations= $reservationRepository->findByPackDate($request->query->get('pack'), $date_debut, $date_fin);
//          dd($reservations);
            $requete = true;
            return $this->render('front/verif_dispo.html.twig', [
                "reservations"=>$reservations,
                "requete"=>$requete,
                'packs'=>$packs,
                'resa_min'=>$resa_min,
                'resa_max'=>$resa_max,
            ]);

        endif;
        $requete = false;


//    return $this->render('front/verif_dispo.html.twig',[
//        'packs'=>$packs,
//        'resa_min'=>$resa_min,
//        'resa_max'=>$resa_max,
//        "requete"=>$requete,
//    ]);
        return $this->render('laurence/reservation.html.twig',[
            'packs'=>$packs,
            'resa_min'=>$resa_min,
            'resa_max'=>$resa_max,
            "requete"=>$requete,
        ]);


    }




    // -------------------- A METTRE FRONTCONTROLLER --------------------//
    // une fois qu'il y aura une page qui montre tous les packs
    /**
     * @Route("/detail_pack/{id}", name="detail_pack")
     */
    public function detail_pack(Pack $pack, $id)
    {
        return $this->render('laurence/detail_pack.html.twig', [
            'pack'=>$pack
        ]);
    }










}
