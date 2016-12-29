<?php

namespace IUT\NuptiasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Ldap\Adapter\ExtLdap\Collection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MariageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
        ->add('date',               DateType::class)
        ->add('ville',              TextType::class)
        ->add('nbInvites',          IntegerType::class)
        ->add('description',        TextareaType::class, array('required' => false))
        ->add('budget',             IntegerType::class)
        ->add('budgetSalle',        IntegerType::class, array('required' => false))
        ->add('budgetTraiteur',     IntegerType::class, array('required' => false))
        ->add('budgetPhotographe',  IntegerType::class, array('required' => false))
        ->add('budgetDJ',           IntegerType::class, array('required' => false))
        ->add('invites',            CollectionType::class, array(
          'entry_type'    => InviteType::class,
          'allow_add'     => true,
          'allow_delete'  => true,
          'by_reference'  => false
        ))
        ->add('Enregistrer',      SubmitType::class);
      }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IUT\NuptiasBundle\Entity\Mariage'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'iut_nuptiasbundle_mariage';
    }


}
