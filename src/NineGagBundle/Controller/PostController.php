<?php

namespace NineGagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{
    public function afficherPostsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $postRepository = $em->getRepository('NineGagBundle:Post');

        $postList = $postRepository->findAll();

        return $this->render('NineGagBundle:Post:homepage.html.twig', [
            'postList' => $postList
        ]);

    }
}
