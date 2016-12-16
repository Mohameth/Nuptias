<?php

namespace NuptiasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationPrestataireType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
      $builder
        ->add('nom',          TextType::class)
        ->add('rue',          TextType::class)
        ->add('codePostal',   IntegerType::class)
        ->add('ville',        TextType::class, array('required' => false))
        ->add('services'      TextareaType::class, array('required' => false))
        ->add('tel',          IntegerType::class);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

    }

    public function getBlockPrefix()
    {
        return 'app_prestataire_registration';
    }

}
