<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Consultation;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType; 

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('psychiatrist', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'query_builder' => function (UserRepository $userRepository) {
                    return $userRepository->getUsersByRoleQueryBuilder('ROLE_PSYCHIATRE');
                },
            ])
            ->add('created_at', DateTimeType::class, [
                'label' => 'Consultation Date and Time',
                'widget' => 'single_text',
                'html5' => true, 
                'attr' => [
                    'class' => 'form-control', 
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'), 
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
