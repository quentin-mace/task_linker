<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_home')]
final class ProjectController extends AbstractController
{
    #[Route('/', name: '')]
    #[Route('/projets', name: '_index')]
    #[Route('/{id}/delete', name: '_delete')]
    public function index(?Project $project, ProjectRepository $projectRepository, TaskRepository $taskRepository, EntityManagerInterface $entityManager): Response
    {
        if ($project) {
            $entityManager->remove($project);
            $tasks = $taskRepository->findBy(['project' => $project]);
            foreach ($tasks as $task) {
                $entityManager->remove($task);
            }
            $entityManager->flush();
        }

        $projects = $projectRepository->findAll();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}', name: '_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('projects/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[Route('/create', name: '_create')]
    public function edit(?Project $project, Request $request, EntityManagerInterface $entityManager): Response
    {

        $project ??= new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $project
                ->setStartDate(new DateTimeImmutable())
                ->setIsArchived(false);

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_home_show', ['id' => $project->getId()]);
        }

        return $this->render('projects/edit.html.twig', [
            'form' => $form,
            'action' => $project->getId() ? 'edit' : 'create',
            'project' => $project,
        ]);
    }
}
