<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Role;
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
        TaskFactory::createMany(5);
        TagFactory::createMany(5);
    }

    private function generateRoles(ObjectManager $manager): void
    {
        $admin = new Role();
        $admin->setName('admin');
        $manager->persist($admin);

        $user = new Role();
        $user->setName('user');
        $manager->persist($user);

        $manager->flush();
    }

    private function generateEmployees(ObjectManager $manager): void
    {
        $admin = new Employee();
        $admin->setFirstName('admin');
        $admin->setLastName('admin');
        $admin->setEmail('admin@test.io');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setRole($this->roleRepository->findOneBy(['name' => 'admin']));
        $admin->setIsActive(true);
        $admin->setContract('CDI');
        $admin->setHiringDate(new DateTimeImmutable('2020-01-01'));
        $manager->persist($admin);

        $user = new Employee();
        $user->setFirstName('user');
        $user->setLastName('user');
        $user->setEmail('user@test.io');
        $user->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $user->setRole($this->roleRepository->findOneBy(['name' => 'user']));
        $user->setIsActive(true);
        $user->setContract('CDD');
        $user->setHiringDate(new DateTimeImmutable('2025-01-01'));
        $manager->persist($user);

        $manager->flush();
    }
}
