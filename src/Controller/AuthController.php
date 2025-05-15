<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Role;
use App\Form\RegistrationFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function register(?Employee $employee, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher, ValidatorInterface $validator): Response
    {
        $employee ??= new Employee();
        $form = $this->createForm(RegistrationFormType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $employee->setPassword($hasher->hashPassword($employee, $form->get('plain_password')->getData()));

            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form,
        ]);
    }
}
