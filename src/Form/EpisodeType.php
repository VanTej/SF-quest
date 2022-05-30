<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('program', EntityType::class, [
                'class' => Program::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.title', 'ASC');
                },
                'multiple' => false,
                'expanded' => true,
                'by_reference' => false,
                'choice_label' => 'title',
                'label' => 'Série',
            ])
            ->add('season', EntityType::class, [
                'class' => Season::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->join('s.program', 'p')
                        ->orderBy('p.title', 'ASC');
                },
                'multiple' => false,
                'expanded' => true,
                'by_reference' => false,
                'choice_label' => 'number',
                'label' => 'Numéro de la saison',
            ])
            ->add('number', IntegerType::class, [
                'label' => 'Numéro de l\'épisode',
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => 'Synopsis',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
