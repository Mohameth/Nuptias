<?php

namespace IUT\NuptiasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;//Pour hériter de la classe Controller
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

//Inclusion des classes (entity) pour gérer les données
use IUT\NuptiasBundle\Entity\Mariage;
use IUT\NuptiasBundle\Form\MariageType;
use IUT\NuptiasBundle\Entity\Invite;
use IUT\NuptiasBundle\Form\InvitesType;

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
                  array('name' => $i->getNom())
                ),
              'text/html'
          );

      $this->get('mailer')->send($message);
      }

      return $this->render('IUTNuptiasBundle:Nuptias:InviteSucces.html.twig');


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
}
