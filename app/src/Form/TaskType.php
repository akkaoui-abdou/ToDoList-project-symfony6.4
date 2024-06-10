<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('startDate', DateType::class, [
                'label'=>'Date début',
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now")])
            ->add('endDate',DateType::class, [
                'label'=>'Date fin',
                'input' => 'datetime',
                'widget' => 'single_text',
                'data' => new \DateTime("now")]
                )
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'En cours' => "En cours",
                    'Terminé' => "Terminé",
                    'Annullé' => "Annullé",
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
