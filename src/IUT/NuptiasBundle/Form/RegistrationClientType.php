<?php

namespace NuptiasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class RegistrationClientType extends AbstractType {

  /*public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('name');
  }*/

  public function getParent() {
    return 'FOS\UserBundle\Form\Type\RegistrationFormType';
  }

  public function getBlockPrefix() {
    return 'app_client_registration';
  }


}
