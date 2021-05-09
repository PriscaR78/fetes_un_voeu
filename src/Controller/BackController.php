<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class BackController extends AbstractController
{

    /**
     * @Route("/", name="backoffice")
     */
    public function backoffice()
    {
        return $this->render('backoffice.html.twig');
    }


    /**
     * @Route("/utilisateur", name="utilisateurs")
     */
    public function utilisateurs(UserRepository $repository)
    {
        $utilisateurs=$repository->findAll();

        return $this->render('back/utilisateurs.html.twig', [
            'utilisateurs'=>$utilisateurs
        ]);
    }


    /**
     * @Route("/deleteuser/{id}", name="deleteuser")
     */
    public function delete_user(User $user, EntityManagerInterface $manager)
    {
        //on appelle l'entité User pour ne pas avoir du user repository

        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', "L'utilisateur a bien été supprimé");
        return $this->redirectToRoute('utilisateurs');
    }
//autre solution
//    /**
//     * @Route("/deleteuser/{id}", name="deleteuser")
//     */
//    public function delete_user_repository(UserRepository $repository, $id, EntityManagerInterface $manager)
//    {
//        $user=$repository->find($id);
//
//        $manager->remove($user);
//        $manager->flush();
//
//        $this->addFlash('success', "L'utilisateur a bien été supprimé");
//        return $this->redirectToRoute('utilisateurs');
//    }
}
