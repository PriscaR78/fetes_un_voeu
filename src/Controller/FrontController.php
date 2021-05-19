<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\PackRepository;
use App\Repository\UserRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

// -----------------------------------------    RESERVATION    -----------------------------------------//
    // --------------  VERIFICATION DISPONIBILITE + RESERVATION  ------------------//

    /**
     * @Route("/verif_dispo", name="verif_dispo")

     */
    public function verif_dispo(EntityManagerInterface $manager,Request $request, ReservationRepository $reservationRepository, PackRepository $packRepository, UserRepository $userRepository, Reservation $reservation=null)
    {

        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        $reservation->setUser($this->getUser());
        $resa_min= date('Y-m-d', time());
        $resa_max = date('Y-m-d',time() + (365 * 24 * 60 * 60 ))   ;
        $requete = false;
        $packs=$packRepository->findAll();


        if ($form->isSubmitted() && $form->isValid()):  // formulaire post ajout_reservation !
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

                $resa=$reservationRepository->find($reservation->getId());

                $this->addFlash("success", "Votre réservation est en cours, merci de procéder au paiement pour la finaliser");
                return $this->render("front/pagePaiement.html.twig",[
                    'reservation'=>$resa]);

            else:
                $this->addFlash("danger", "Ce pack n'est pas disponible à cette date.");

                return $this->redirectToRoute('verif_dispo');

            endif;
        endif;

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
//                                                      AJOUTER CONDITION SI date_debut==date_fin
            if ($timstamp_fin < $timstamp_debut):
//              dump($timstamp_fin);
                $this->addFlash('danger', "Les dates renseignées ne sont pas cohérentes");
            endif;


            // ------------ ENVOI DONNEES FORMULAIRE VERS REPOSITORY ------------ //

            $date_debut= new \DateTime($request->query->get('date_debut'));
            $date_fin= new \DateTime($request->query->get('date_fin'));

            $packs=$packRepository->findAll();
            $reservations= $reservationRepository->findByPackDate($request->query->get('pack'), $date_debut, $date_fin);
            if (!$reservations):
                $this->addFlash('info', "Ce pack est disponible aux dates sélectionnées");
            endif;
//          dd($reservations);
            $requete = true;
            return $this->render('back/reservation.html.twig', [
                "reservations"=>$reservations,
                'formResa'=>$form->createView(),
                "requete"=>$requete,
                'packs'=>$packs,
                'resa_min'=>$resa_min,
                'resa_max'=>$resa_max,
            ]);

        endif;
        $requete = false;

        return $this->render('back/reservation.html.twig',[
            'formResa'=>$form->createView(),
            "requete"=>$requete,
            'packs'=>$packs,
            'resa_min'=>$resa_min,
            'resa_max'=>$resa_max,
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


