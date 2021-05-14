<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/inscription", name="inscription")
     * @Route("/modifprofil/{id}", name="modifprofil")
     */
    public function registration( User $user=null, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        if (!$user):
        $user=new User();
        $mode=true; // = create
            else:
            $mode=false; // = modifier ou l'inverse


        endif;
        $form=$this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):
            $hash=$encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);
            $user->setMajorite(true);
            $manager->persist($user);
            $manager->flush();

            if($mode==true):
            $this->addFlash('success', "Félicitations, votre inscription a bien été enregistrée.");
                return $this->redirectToRoute('login');
                else:
                $this->addFlash('success', "Vos modification ont bien été enregistrées.");
                    return $this->redirectToRoute('profil');
            endif;


        endif;

        return $this->render('security/inscription.html.twig',[
            'form'=>$form->createView(),
            'mode'=>$mode
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $lastuser=$authenticationUtils->getLastUsername();

        return $this->render('security/connexion.html.twig',[
            'lastuser'=>$lastuser
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/supp_user/{id}", name="supp_user")
     */
    public function suppression_user($id, User $user, EntityManagerInterface $manager, ReservationRepository $reservationRepository)
    {
        $resa_client=$reservationRepository->findResaUser($id);
        if ($resa_client):
        $this->addFlash('danger', 'Ce client a des réservations en cours, la suppression de son compte n\'est pas possible avant l\'annulation de ces réservations');
        else:
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'Le compte client a bien été supprimé');
        endif;
        return $this->redirectToRoute('gestion_clients');
    }
















}
