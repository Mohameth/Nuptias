<?php

namespace IUT\NuptiasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RegistrationPrestataireType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
      $builder
        ->add('nom',          TextType::class)
        ->add('ville',        TextType::class)
        ->add('rue',          TextType::class)
        ->add('codePostal',   IntegerType::class)
        ->add('tel',          TextType::class);
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
