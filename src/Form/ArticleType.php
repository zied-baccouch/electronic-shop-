<?php

namespace App\Form;
use App\Entity\categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('categorie', EntityType::class, [
                    'class'=> Categorie::class,
                    'choice_label' => 'titre',
                    'label' =>'Catégorie'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
