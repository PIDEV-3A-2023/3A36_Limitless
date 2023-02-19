<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackendController extends AbstractController
{
    #[Route('/backend', name: 'app_backend')]
    public function index(): Response
    {
        return $this->render('back.html.twig', [
            'controller_name' => 'BackendController',
        ]);
    }
}
