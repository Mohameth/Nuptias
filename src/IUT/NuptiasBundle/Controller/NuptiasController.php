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
use IUT\NuptiasBundle\Form\MariageType;

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

    /**
    * Prend en paramètre un id de mariage
    * Si celui ci vaut 0, on renvoie l'utilisateur sur une page ou il pourra
    * choisir le mariage à modifier
    * Sinon on le redirige vers le mariage d'identifiant donnée.
    */
    public function DashBoardAction($id = 0) {
        //Recupération de l'utilisateur
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getManager()->getRepository('IUTNuptiasBundle:Mariage');

        //Redirection vers le mariage d'identifant donnée
        if ($id != 0) {
          $mariage = $repository->find($id);
          return $this->render('IUTNuptiasBundle:Nuptias:Dash.html.twig', array(
            'mariage' => $mariage));
        }

        //Récupération de la liste de tous les mariages
        $listeMariage = $repository->findBy(
          array('client' => $user->getId()),
          array('date' => 'desc')
        );

        if ($listeMariage == null) {
          return $this->render('IUTNuptiasBundle:Nuptias:Dash.html.twig', array(
              'mariage' => null));
        }
        //Si il y a plus d'un mariage on doit choisir lequel gérer
        if (count($listeMariage) > 1)
          return $this->render('IUTNuptiasBundle:Nuptias:mariages.html.twig', array(
              'listeMariage' => $listeMariage));

        //Sinon on le redirige vers le seul mariage entamé.
        return $this->render('IUTNuptiasBundle:Nuptias:Dash.html.twig', array(
            'mariage' => $listeMariage[0]));
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
      $user = $this->container->get('security.token_storage')->getToken()->getUser();

      $mariage->setClient($user);

      // Création du formulaire de création de mariage
      $form = $this->get('form.factory')->create(MariageType::class, $mariage);


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
          //return $this->redirectToRoute('iut_nuptias_dashBoard', array('id' => $mariage->getId()));
          return $this->DashBoardAction();
        }
      }

      return $this->render('IUTNuptiasBundle:Nuptias:mariage.html.twig', array('form' => $form->createView()));
    }
}
