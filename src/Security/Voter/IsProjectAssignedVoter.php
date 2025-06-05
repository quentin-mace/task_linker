<?php

namespace App\Security\Voter;

use App\Entity\Employee;
use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IsProjectAssignedVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'project.is_assigned' === $attribute && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $employee = $token->getUser();
        if (!$employee instanceof Employee) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $employee->getRoles())) {
            return true;
        }

        /* @var Project $subject */
        $employeeProjects = $employee->getProjects();
        foreach ($employeeProjects as $employeeProject) {
            if ($employeeProject->getId() === $subject->getId()) {
                return true;
            }
        }

        return false;
    }
}