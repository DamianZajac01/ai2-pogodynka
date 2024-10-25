<?php

namespace App\Form;

use App\Entity\Localization;
use App\Entity\Weather;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeatherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('temperature', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Enter temperature'
                ]
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'placeholder' => 'Enter description'
                ]
            ])
            ->add('date', null, [
                'data' => new \DateTime(),
            ])
            ->add('localization', EntityType::class, [
                'class' => Localization::class,
                'choice_label' => 'city',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Weather::class,
        ]);
    }
}
