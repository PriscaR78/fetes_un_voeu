<?php

namespace App\Controller;

use App\Entity\Pack;
use App\Form\PackType;
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

    /**
     * @Route("/ajout_pack", name="ajout_pack")
     */
    public function ajout_pack(Request $request, EntityManagerInterface $manager)
    {
        $pack = new Pack();

        $form=$this->createForm(PackType::class, $pack);
        $form->handleRequest($request);
                                                    // VOIR AVEC CESAIRE SI REPETITION DU CODE POUR 3 IMAGES
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
                endif;
                    $pack->setImage2($nomImage2);


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



//        A DECOMMENTER QUAND ROUTE EXISTERA
//        return $this->redirectToRoute("gestion_pack");
    endif;

        $this->addFlash("success", "Le pack a bien été ajouté.");
//    VERSION PROVISOIRE
        return $this->render('laurence/ajout_pack.html.twig', [
            'formPack'=>$form->createView()
        ]);

//        VERSION DEFINITIVE
//    return $this->render('back/pack/ajout_pack', [
//        'formPack'=>$form->createView()
//    ]);

    }
















}
