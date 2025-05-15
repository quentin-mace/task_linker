<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register', name: 'app_register')]
final class RegisterController extends AbstractController
{
    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->render('register/index.html.twig', [
        ]);
    }
}
