<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/task', name: 'app_task')]
final class TaskController extends AbstractController
{
    #[Route('/{id}/edit', name: '_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[Route('/create', name: '_create')]
    public function edit(
        ?Task $task,
        #[MapQueryParameter] int $projectId,
        EntityManagerInterface $entityManager,
        ProjectRepository $projectRepository,
        Request $request,
    ): Response
    {
        $project = $projectRepository->find($projectId);
        $task ??= new Task();
        $form = $this->createForm(TaskType::class, $task, ['project' => $project]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $task->setProject($project);

            $entityManager->persist($task);
            $entityManager->flush();
            return $this->redirectToRoute('app_home_show', ['id' => $project->getId()]);
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form,
            'action' => $task->getId() ? 'edit' : 'create',
            'task' => $task,
            'project' => $project,
        ]);
    }
}
