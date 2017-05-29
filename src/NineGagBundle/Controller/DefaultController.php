<?php

namespace NineGagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NineGagBundle:Default:index.html.twig');
    }
}
