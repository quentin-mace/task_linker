<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dispatch', name: 'app_dispatch')]
final class DispatchController extends AbstractController
{
    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->render('dispatch/index.html.twig', [
            'controller_name' => 'DispatchController',
        ]);
    }
}
