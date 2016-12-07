<?php

namespace IUT\NuptiasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;//Pour hériter de la classe Controller
use Symfony\Component\HttpFoundation\Request;

//Inclusion des types pour les formulaires
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

//Inclusion des classes (entity) pour gérer les données
use IUT\NuptiasBundle\Entity\Mariage;

class NuptiasController extends Controller
{
    public function indexAction()
    {
        return $this->render('IUTNuptiasBundle:Nuptias:index.html.twig');
    }

    public function packAction()
    {
        return $this->render('IUTNuptiasBundle:Nuptias:pack.html.twig');
    }

    public function DashBoardAction() {
        return $this->render('IUTNuptiasBundle:Nuptias:Dash.html.twig');
    }

    public function organisationAction() {
        return $this->render('IUTNuptiasBundle:Nuptias:Org.html.twig');
    }

    public function invitesAction() {
        return $this->render('IUTNuptiasBundle:Nuptias:Invites.html.twig');
    }

    public function mariageAction(Request $request) {
      //TODO: Check si la personne est connecté et à déjà un mariage

      $mariage = new Mariage();
      $mariage->setNbInvites(50);//Par défaut 50 invités

      // Création du formulaire de création de mariage
      $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $mariage);
      $formBuilder
        ->add('date',               DateType::class)
        ->add('ville',              TextType::class)
        ->add('nbInvites',          IntegerType::class)
        ->add('description',        TextareaType::class, array('required' => false))
        ->add('budget',             IntegerType::class)
        ->add('budgetSalle',        IntegerType::class, array('required' => false))
        ->add('budgetTraiteur',     IntegerType::class, array('required' => false))
        ->add('budgetPhotographe',  IntegerType::class, array('required' => false))
        ->add('budgetDJ',           IntegerType::class, array('required' => false))
        ->add('Enregistrer et voir le récapitulatif',      SubmitType::class);
      $form = $formBuilder->getForm();

      // Le bouton "submit" a rechargé la page avec toutes les infos (en POST)
      // Il faut les enregistrer
      if($request->isMethod('POST')) {
        // Le formulaire hydrate la variable $mariage avec les bonnes valeurs
        $form->handleRequest($request);

        if ($form->isValid()) {
          // Enregistrement
          $em = $this->getDoctrine()->getManager();
          $em->persist($mariage);
          $em->flush();

          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
          return $this->redirectToRoute('iut_nuptias_dashBoard', array('id' => $mariage->getId()));
        }
      }

      return $this->render('IUTNuptiasBundle:Nuptias:mariage.html.twig', array('form' => $form->createView()));
    }
}
