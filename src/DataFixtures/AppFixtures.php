<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Role;
use App\Factory\EmployeeFactory;
use App\Factory\ProjectFactory;
use App\Factory\RoleFactory;
use App\Factory\StatusFactory;
use App\Factory\TagFactory;
use App\Factory\TaskFactory;
use App\Repository\RoleRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly RoleRepository $roleRepository, private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $this->generateRoles($manager);
        $this->generateEmployees($manager);
        $this->generateProjects(10);
    }

    private function generateRoles(): void
    {
        RoleFactory::createOne([
            'name' => 'admin',
        ]);

        RoleFactory::createOne([
            'name' => 'user',
        ]);
    }

    private function generateEmployees(): void
    {
        EmployeeFactory::createOne([
            'firstName' => 'admin',
            'lastName' => 'admin',
            'email' => 'admin@test.io',
            'password' => 'admin',
            'role' => $this->roleRepository->findOneBy(['name' => 'admin']),
            'isActive' => true,
            'contract' => 'CDI',
            'hiringDate' => new DateTimeImmutable('2020-01-01'),
        ]);

        EmployeeFactory::createOne([
            'firstName' => 'user',
            'lastName' => 'user',
            'email' => 'user@test.io',
            'password' => 'user',
            'role' => $this->roleRepository->findOneBy(['name' => 'user']),
            'isActive' => true,
            'contract' => 'CDD',
            'hiringDate' => new DateTimeImmutable('2020-01-01'),
        ]);
    }

    private function generateProjects(int $number): void
    {
        for ($i = 0; $i < $number; $i++) {
            $this->generateProject();
        }
    }

    private function generateProject(): void
    {
        $project = ProjectFactory::createOne();

        $todo = StatusFactory::createOne([
            'name' => 'To Do',
            'project' => $project,
        ]);
        $doing = StatusFactory::createOne([
            'name' => 'Doing',
            'project' => $project,
        ]);
        $done = StatusFactory::createOne([
            'name' => 'Done',
            'project' => $project,
        ]);

        TaskFactory::createMany(2, [
            'status' => $todo,
            'project' => $project,
        ]);
        TaskFactory::createOne([
            'status' => $doing,
            'project' => $project,
        ]);
        TaskFactory::createOne([
            'status' => $done,
            'project' => $project,
        ]);

        TagFactory::createMany(3, [
            'project' => $project,
        ]);
    }
}
