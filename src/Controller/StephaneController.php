<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StephaneController extends AbstractController
{
    /**
     * @Route("/stephane", name="stephane")
     */
    public function index(): Response
    {
        return $this->render('stephane/index.html.twig', [
            'controller_name' => 'StephaneController',
        ]);
    }
}
