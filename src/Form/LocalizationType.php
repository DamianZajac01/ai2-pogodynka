<?php

namespace App\Form;

use App\Entity\Localization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'Spain' => 'ES',
                    'Italy' => 'IT',
                    'United States' => 'US',
                    'United Kingdom' => 'GB',
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter city name'
                ]
            ])
            ->add('latitude', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Enter latitude'
                ]
            ])
            ->add('longitude', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Enter longitude'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Localization::class,
        ]);
    }
}
