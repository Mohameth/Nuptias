<?php

namespace IUT\NuptiasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
