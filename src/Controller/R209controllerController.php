<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class R209controllerController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/r209controller', name: 'app_r209controller')]
    public function index(): Response
    {
        return $this->render('r209controller/index.html.twig', [
            'controller_name' => 'R209controllerController',
        ]);
    }
}
