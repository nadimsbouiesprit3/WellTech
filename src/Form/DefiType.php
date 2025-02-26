<?php

namespace App\Form;

use App\Entity\Defi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('points')
            ->add('statut')
            ->add('date_debut', null, [
                'widget' => 'single_text',
            ])
            ->add('date_fin', null, [
                'widget' => 'single_text',
            ])
            ->add('conditions')
            ->add('type')
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
            'data_class' => Defi::class,
        ]);
    }
}
