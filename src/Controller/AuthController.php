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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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

    #[Route(path: '/login', name: '_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: '_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
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
