<?php

namespace App\Form;

use App\Entity\Defi;
use App\Entity\Progression;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgressionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut')
            ->add('progression')
            ->add('date_completion', null, [
                'widget' => 'single_text',
            ])
            ->add('user_id', EntityType::class, [
                'class' => user::class,
                'choice_label' => 'id',
            ])
            ->add('defi_id', EntityType::class, [
                'class' => Defi::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Progression::class,
        ]);
    }
}
