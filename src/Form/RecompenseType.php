<?php

namespace App\Form;

use App\Entity\Recompense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecompenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('points_requis')
            ->add('type')
            ->add('statu')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('updated_at', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recompense::class,
        ]);
    }
}
