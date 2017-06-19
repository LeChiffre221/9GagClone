<?php

namespace NineGagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use NineGagBundle\Entity\Comment;
use NineGagBundle\Form\CommentType;
use NineGagBundle\Entity\Post;

class CommentController extends Controller {
    
    private function createListFormComments($post){
        $listFormComment = [];
        $postList = [];
        array_push($postList, $post);
         foreach ($postList as $post) {
            $comment = new Comment();
            $comment->setPost($post);
            $formComment = $this->get('form.factory')->create(CommentType::class, $comment);
            
            $listFormComment[$post->getId()] = $formComment->createView();
        }
        return $listFormComment;
    }

    public function addCommentAction(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository('NineGagBundle:Post')->findOneBy(['id' => $comment->getIdPost()]);
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
        }
        $listFormComment = $this->createListFormComments($post);
        return new JsonResponse(array('message' => 'success!', 'result' => $this->renderView('NineGagBundle:Comment:comments.html.twig', ['post' => $post, 'listFormComment' => $listFormComment])), 200);
    }

}
