<?php

namespace IUT\NuptiasBundle\Controller;

use IUT\NuptiasBundle\Entity\Service;
use IUT\NuptiasBundle\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;//Pour hériter de la classe Controller

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//Inclusion des classes (entity) pour gérer les données
use IUT\NuptiasBundle\Entity\Mariage;
use IUT\NuptiasBundle\Form\MariageType;
use IUT\NuptiasBundle\Entity\Invite;
use IUT\NuptiasBundle\Form\InvitesType;
use IUT\NuptiasBundle\Entity\Salle;
use IUT\NuptiasBundle\Form\SalleType;
use IUT\NuptiasBundle\Entity\Traiteur;
use IUT\NuptiasBundle\Form\TraiteurType;
use IUT\NuptiasBundle\Entity\DJ;
use IUT\NuptiasBundle\Form\DJType;
use IUT\NuptiasBundle\Entity\Deco;
use IUT\NuptiasBundle\Form\DecoType;

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

    public function invitesAction(Request $request, $id_mariage) {
      //Recupération de l'utilisateur
      $user = $this->container->get('security.token_storage')->getToken()->getUser();
      $repository = $this->getDoctrine()->getManager()->getRepository('IUTNuptiasBundle:Mariage');

      //récuperation du mariage si id présent
      if ($id_mariage != 0) {
        $mariage = $repository->find($id_mariage);
      }
      if ($mariage == null) return new Response("ERREUR : Ce mariage n'existe pas");
      if ($mariage->getClient()->getId() != $user->getId()) {
        return new Response("ERREUR : Vous n'avez pas acces à ce mariage");
      }

      //Création de l'invite et de la réponse par défaut
      $invite = new Invite();
      $invite->setReponse("En attente");

      //Création du formulaire pour cet invite
      $form = $this->get('form.factory')->create(InvitesType::class, $invite);

      //L'utilisateur a cliqué sur "Ajouter", le formulaire doit être géré.
      if($request->isMethod('POST')) {
        // Le formulaire hydrate la variable $mariage (et $invite aussi du coup) avec les bonnes valeurs
        $form->handleRequest($request);

        foreach ($mariage->getInvites() as $inviteTemp) {
          if ($inviteTemp->getMail() == $invite->getMail()) {
            return new Response("ERREUR : Un invité avec cette adresse mail a déjà été enregistré.");
          }
        }

        if ($form->isValid()) {
          // Enregistrement
          $mariage->addInvite($invite);
          $em = $this->getDoctrine()->getManager();
          $em->persist($mariage);
          $em->flush();

          $request->getSession()->getFlashBag()->add('notice', 'Invite bien enregistrée.');
        }
      }

      return $this->render('IUTNuptiasBundle:Nuptias:Invites.html.twig',array(
                           'form' => $form->createView(),
                           'id_mariage' => $id_mariage,
                           'invites' => $mariage->getInvites(),
                           'nbInvites' => $mariage->getNbInvites())
        );
    }

    public function deleteInviteAction(Request $request, $id_mariage, $id_invite) {
      //Recupération de l'utilisateur
      $user = $this->container->get('security.token_storage')->getToken()->getUser();
      $repository = $this->getDoctrine()->getManager()->getRepository('IUTNuptiasBundle:Mariage');

      //récuperation du mariage si id présent
      if ($id_mariage != 0 && $id_invite != 0) {
        $mariage = $repository->find($id_mariage);

        if ($mariage != null) {
          if ($mariage->getClient()->getId() != $user->getId()) {
            return new Response("ERREUR : Vous n'avez pas acces à ce mariage");
          }

          //Recherche du bon invite
          foreach ($mariage->getInvites() as $inviteTemp) {
            if ($inviteTemp->getId() == $id_invite) {
              $invite = $inviteTemp;
              break;
            }
          }

          //Si on l'a trouvé on le supprime
          if (isset($invite)) {
            $mariage->removeInvite($invite);

            $em = $this->getDoctrine()->getManager();
            $em->persist($mariage);
            $em->flush();
          }
        }
        else {
          return new Response("ERREUR : Ce mariage n'existe pas/plus.");
        }
      }

      return $this->invitesAction($request, $id_mariage);
    }


    public function sendInviteAction($id_mariage) {
      //Recupération de l'utilisateur
      $user = $this->container->get('security.token_storage')->getToken()->getUser();
      $repository = $this->getDoctrine()->getManager()->getRepository('IUTNuptiasBundle:Mariage');

      //récuperation du mariage si id présent
      if ($id_mariage != 0) {
        $mariage = $repository->find($id_mariage);
      }
      if (! isset($mariage) || $mariage == null) return new Response("ERREUR : Ce mariage n'existe pas");
      if ($mariage->getClient()->getId() != $user->getId()) {
        return new Response("ERREUR : Vous n'avez pas acces à ce mariage");
      }


      //recuperation de l'instance de SwiftMailer pour ecrire un message
      $message = \Swift_Message::newInstance();
      $message->setSubject('Invitation au mariage de ' . $user->getUsername() );
      $message->setFrom('invitation@tonmariagedereve.fr');
      //definition de l'objet et de l'expediteur

      //recuperation des invité
      $invites = $mariage->getInvites();

      //envoie à tous les invités enregistrer
      foreach ($invites as $i ) {
        $message->setTo($i->getMail());
        $message->setBody(
        $this->renderView(
                  'IUTNuptiasBundle:Mail:Annonce.html.twig',
                  array('name' => $i->getNom(),
                        'id_mariage' => $mariage->getId(),
                        'id_invite' => $i->getId())
                ),
              'text/html'
          );

      $this->get('mailer')->send($message);
      }

      return $this->render('IUTNuptiasBundle:Nuptias:SuccessMessage.html.twig',
                            array('message' => 'Toutes vos invitations ont été envoyé avec succés')
                          );


    }

    public function reponseAction($id_mariage,$id_invite,$id_reponse){
      $repository = $this->getDoctrine()->getManager()->getRepository('IUTNuptiasBundle:Mariage');

      //recuperation du mariage
      if ($id_mariage != 0 && $id_invite != 0) {
        $mariage = $repository->find($id_mariage);

        if ($mariage != null) {

          //Recherche du bon invite
          foreach ($mariage->getInvites() as $inviteTemp) {
            if ($inviteTemp->getId() == $id_invite) {
              $invite = $inviteTemp;
              break;
            }
          }

          //Si on l'a trouvé on modifie sa reponse
          if (isset($invite)) {
            if ($id_reponse == 1) {
              $invite->setReponse('Positive');
            } else if ($id_reponse == 0) {
              $invite->setReponse('Négative');
            } else {
              $invite->setReponse('En attente');
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($invite);
            $em->flush();
          }
          return $this->render('IUTNuptiasBundle:Nuptias:SuccessMessage.html.twig',
                                array('message' => 'Votre réponse a bien été enregistrée')
                              );
        }
        else {
          return new Response("ERREUR : Ce mariage n'existe pas/plus.");
        }
      }
    }

    public function deleteMariageAction($id) {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('IUTNuptiasBundle:Mariage');

      //Récupération du mariage
      if ($id != 0) {
        $mariage = $repository->find($id);
        if ($mariage != null) {
          $em->remove($mariage);
          $em->flush();
          return $this->render('IUTNuptiasBundle:Nuptias:Dash.html.twig', array(
                               'mariage' => null,
                               'succesSuppression' => "true")
          );
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

          $request->getSession()->getFlashBag()->add('notice', 'Mariage bien enregistrée.');
          //return $this->redirectToRoute('iut_nuptias_dashBoard', array('id' => $mariage->getId()));
          return $this->DashBoardAction();
        }
      }

      return $this->render('IUTNuptiasBundle:Nuptias:mariage.html.twig', array(
          'pack' => $request->attributes->get('pack'),
          'form' => $form->createView()));
    }

    public function serviceAction(Request $request) {
      $user = $this->container->get('security.token_storage')->getToken()->getUser();
      /* TODO: Trouver ses services et les afficher, si aucun lui proposer d'en créer un nouveau */

      if ($user == null) return new Response("ERREUR : Vous devez être connecté.");
      //Récupération des services
      //Il faut que l'utilisateur soit un prestataire
      if (get_class($user) != 'IUT\NuptiasBundle\Entity\Prestataire') {
        return new Response("ERREUR : Seul un prestataire peut gérer un service");
      }

      $typeService = array('Traiteur', 'Salle', 'Traiteur', 'DJ', 'Deco',);
      $listeService = array();
      $em = $this->getDoctrine()->getManager();

      foreach ($typeService as $type) {
        //Le repository ne se met pas à jour --
        $repository = $em->getRepository('IUTNuptiasBundle:'.$type);
        $listeService[] = $repository->findBy(
          array('prestataire' => $user->getId())
        );

        //var_dump($listeService);
      }

      return $this->render('IUTNuptiasBundle:Nuptias:service.html.twig', array(
        'listeService' => $listeService));
    }

    public function choixServiceAction(Request $request) {
    //Affichage des services possibles (expanded pour afficher des radio-boutons)
      $form = $this->createFormBuilder()
        ->add('type', ChoiceType::class, array(
          'choices' => array(
            'Salle' => 'Salle',
            'Traiteur' => 'Traiteur',
            'DJ' => 'DJ',
            'Decoration' => 'Deco',),
          'expanded' => true))
        ->add('Continuer', SubmitType::class)
        ->getForm();
      $form->handleRequest($request);

      //Si tout est bon on le renvoie vers la page de création de service avec le bon type.
      if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        return $this->redirectToRoute('iut_nuptias_create_service', array('type' => $data['type']));
      }

      return $this->render('IUTNuptiasBundle:Nuptias:choixService.html.twig', array(
        'form' => $form->createView()
      ));
    }

    public function createServiceAction(Request $request) {
      $user = $this->container->get('security.token_storage')->getToken()->getUser();

      if ($user == null) return new Response("ERREUR : Vous devez vous connecter pour créer un service.");
      //Il faut que l'utilisateur soit un prestataire
      if (get_class($user) != 'IUT\NuptiasBundle\Entity\Prestataire') {
        return new Response("ERREUR : Seul un prestataire peut créer un service");
      }

      $type = $request->query->get('type');
      if (!isset($type) || $type == null) {
        return new Response("ERREUR : Le type de service n'a pas été spécifiée");
      }

      //Création dynamique d'une instance de Service puis d'un formulaire selon le type spécifié
      $refl = new \ReflectionClass('IUT\NuptiasBundle\Entity\\'.$type);
      $instance = $refl->newInstance();
      $formInstanceService = $this->get('form.factory')->create('IUT\NuptiasBundle\Form\\'.$type.'Type', $instance);


      if($request->isMethod('POST')) {
        // Le formulaire hydrate la variable $mariage avec les bonnes valeurs
        $formInstanceService->handleRequest($request);

        if ($formInstanceService->isValid()) {
          // Enregistrement
          $instance->setPrestataire($user);
          $em = $this->getDoctrine()->getManager();
          $em->persist($instance);
          $em->flush();

          $request->getSession()->getFlashBag()->add('notice', 'Service bien enregistrée.');
          //return $this->redirectToRoute('iut_nuptias_dashBoard', array('id' => $mariage->getId()));
          return $this->redirectToRoute('iut_nuptias_service');
        }
      }

      return $this->render('IUTNuptiasBundle:Nuptias:creer_service.html.twig', array(
        'type' => $type,
        'form' => $formInstanceService->createView()
      ));
    }
}
