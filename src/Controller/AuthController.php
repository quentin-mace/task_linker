<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth', name: 'app_dispatch')]
final class AuthController extends AbstractController
{
    #[Route('/dispatch', name: '')]
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
        ]);
    }

    #[Route('/login', name: '_login')]
    public function login(): Response
    {
        return $this->render('auth/index.html.twig', [
        ]);
    }

    #[Route('/register', name: '_register')]
    public function register(): Response
    {
        return $this->render('auth/index.html.twig', [
        ]);
    }
}
