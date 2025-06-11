<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/employee', name: 'app_employee')]
final class EmployeeController extends AbstractController
{
    #[Route('/', name: '_index')]
    #[Route('/{id}/delete', name: '_delete', requirements: ['id' => '\d+'])]
    public function index(?Employee $employee, EmployeeRepository $employeeRepository, EntityManagerInterface $entityManager): Response
    {
        if ($employee) {
            $tasks = $employee->getTasks();
            foreach ($tasks as $task) {
                $task->removeEmployee($employee);
                $entityManager->persist($task);
            }
            $projects = $employee->getProjects();
            foreach ($projects as $project) {
                $project->removeEmployee($employee);
                $entityManager->persist($project);
            }
            $entityManager->remove($employee);
            $entityManager->flush();
        }

        $employees = $employeeRepository->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/{id}', name: '_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Employee $employee, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $form->get('roles')->getData();
            $employee->setRoles([$roles]);
            $entityManager->persist($employee);
            $entityManager->flush();
            return $this->redirectToRoute('app_employee_index');
        }

        return $this->render('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }
}
