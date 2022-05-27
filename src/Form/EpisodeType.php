<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                ])
            ->add('number', IntegerType::class, [
                'label' => 'Numéro',
                ])
            ->add('synopsis', TextType::class, [
                'label' => 'Synopsis',
                ])
            ->add('program', null, [
                'choice_label' => 'title',
                'label' => 'Série',
                ])
            ->add('season', null, [
                'choice_label' => 'number',
                'label' => 'Saison',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
