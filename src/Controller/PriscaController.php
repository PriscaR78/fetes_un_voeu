<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriscaController extends AbstractController
{
    /**
     * @Route("/prisca", name="prisca")
     */
    public function index(): Response
    {
        return $this->render('prisca/index.html.twig', [
            'controller_name' => 'PriscaController',
        ]);
    }
}
