<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Episode;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('program', null, [
                'choice_label' => 'title',
                'label' => 'Série',
            ])
            ->add('season', EntityType::class, [
                'class' => Season::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.number', 'ASC');
                },
                'choice_label' => 'number',
            ])
            ->add('number', IntegerType::class, [
                'label' => 'Numéro',
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('synopsis', TextType::class, [
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
