<?php

namespace NineGagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use NineGagBundle\Entity\Comment;
use NineGagBundle\Form\CommentType;

class CommentController extends Controller {

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
            return $this->redirectToRoute('nine_gag_afficherPosts');
        }
    }

    private function getListPosts() {
        $em = $this->getDoctrine()->getManager();
        $postRepository = $em->getRepository('NineGagBundle:Post');
        return $postRepository->findAll();
    }

    public function editScoreAction(Request $request, int $idPost, int $valueToChange) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $postRepository = $em->getRepository('NineGagBundle:Post');

        $post = $postRepository->findOneBy(['id' => $idPost]);

        $post->setScore($post->getScore() + $valueToChange);
        $em->persist($post);
        $em->flush();

        return new JsonResponse(['message' => 'Success!', 'newScore' => $post->getScore()], 200);
    }

    public function addPostAction(Request $request) {

//        if (!$request->isXmlHttpRequest()) {
//            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
//        }

        $post = new Post();
        $form = $this->get('form.factory')->create(PostType::class, $post);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $post->getImage();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                    $this->getParameter('images_directory'), $fileName
            );
            $post->setImage($fileName);
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('nine_gag_afficherPosts');
        }
        return $this->redirectToRoute('nine_gag_afficherPosts', ['form' => $form]);
    }

}
