<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('duree', ChoiceType::class, [
                'choices' => [
                    '1 mois' => 1,
                    '2 mois' => 2,
                    '3 mois' => 3,
                    '4 mois' => 4,
                    '5 mois' => 5,
                    '6 mois' => 6,
                    '7 mois' => 7,
                    '8 mois' => 8,
                    '9 mois' => 9,
                    '10 mois' => 10,
                    '11 mois' => 11,
                    '12 mois' => 12,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
