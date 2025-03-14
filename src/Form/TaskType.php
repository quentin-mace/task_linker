<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Tag;
use App\Entity\Task;
use App\Repository\StatusRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('deadline', DateType::class, [
                'label' => 'Date',
                'required' => false,
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'query_builder' => function ($repository) use ($options) {
                    /* @var $repository StatusRepository */
                    return $repository->createQueryBuilder('qb')
                        ->where('qb.project = :project')
                        ->setParameter('project', $options['project']);
                }
            ])
            ->add('employees', EntityType::class, [
                'class' => Employee::class,
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'project' => null,
        ]);
    }
}
