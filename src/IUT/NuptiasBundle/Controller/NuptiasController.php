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

use Symfony\Component\HttpFoundation\Response;

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
        //Si il y a plus d'un mariage on doit choisir lequel gérer/consulter
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

    public function invitesAction($id_mariage) {
      //Recupération de l'utilisateur
      $user = $this->container->get('security.token_storage')->getToken()->getUser();
      $repository = $this->getDoctrine()->getManager()->getRepository('IUTNuptiasBundle:Mariage');

      //récuperation du mariage si id présent
      if ($id_mariage != 0) {
        $mariage = $repository->find($id_mariage);
      }
      if ($mariage->getClient()->getId() != $user->getId()) {
        return new Response("ERREUR : Vous n'avez pas acces à ce mariage");
      }

        return $this->render('IUTNuptiasBundle:Nuptias:Invites.html.twig',array(
                              'invites' => $mariage->getInvites(),
                              'nbInvites' => $mariage->getNbInvites())
        );
    }

    public function deleteMariageAction($id) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('IUTNuptiasBundle:Mariage');

        //Récupération du mariage
        if ($id != 0) {
            $mariage = $repository->find($id);
            if($mariage != null) {
                $em->remove($mariage);
                $em->flush();
                return $this->render('IUTNuptiasBundle:Nuptias:Dash.html.twig', array(
                    'mariage' => null,
                    'succesSuppression' => "true"));
            }


        }
        return $this->render('IUTNuptiasBundle:Nuptias:Dash.html.twig', array('succesSupression' => "false"));
    }

    public function mariageAction(Request $request) {

      //On vérifie si un utilisateur est connecté
      $user = $this->container->get('security.token_storage')->getToken()->getUser();

      //Si oui, on vérifie que c'est un client qui n'a pas déjà crée de mariage
      if ($user != null) {
        //Il faut que cela soit un client
        if (get_class($user) != 'IUT\NuptiasBundle\Entity\Client') {
          return new Response("ERREUR : Seul un client peut créer un mariage");
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('IUTNuptiasBundle:Mariage');
        //On recup tous ses mariages
        $listeMariage = $repository->findBy(
          array('client' => $user->getId()),
          array('date' => 'desc')
        );

        if (count($listeMariage) == 1) {
          return new Response("ERREUR : Vous ne pouvez créer qu'un seul mariage simultanéement.");
        }
      }

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

      return $this->render('IUTNuptiasBundle:Nuptias:mariage.html.twig', array(
          'pack' => $request->attributes->get('pack'),
          'form' => $form->createView()));
    }
}
