<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_home')]
final class ProjectController extends AbstractController
{
    #[Route('/', name: '')]
    public function index(ProjectRepository $repository): Response
    {
        $projects = $repository->findAll();

        return $this->render('projects/index.html.twig', [
            'title' => 'Projets',
            'projects' => $projects,
        ]);
    }
}
