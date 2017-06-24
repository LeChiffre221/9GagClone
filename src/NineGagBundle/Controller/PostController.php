<?php

namespace NineGagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use NineGagBundle\Entity\Post;
use NineGagBundle\Form\PostType;
use NineGagBundle\Entity\Comment;
use NineGagBundle\Form\CommentType;

class PostController extends Controller {

    public function afficherPostsAction($formPost = null) {
        if ($formPost == null) {
            $formPost = $this->get('form.factory')->create(PostType::class, new Post());
        }
        $postList = $this->getListPosts();
        $listFormComment = $this->createListFormComments($postList);
        $listScores = $this->createListScores($postList);
        return $this->render('NineGagBundle:Post:homepage.html.twig', [
                    'postList' => $this->getListPosts(),
                    'form' => $formPost->createView(),
                    'listFormComment' => $listFormComment,
                    'listScores' => $listScores
        ]);
    }

    private function getListPosts() {
        $em = $this->getDoctrine()->getManager();
        $postRepository = $em->getRepository('NineGagBundle:Post');
        return $postRepository->findAll();
    }

    private function createListFormComments($postList) {
        $listFormComment = [];
        foreach ($postList as $post) {
            $comment = new Comment();
            $comment->setPost($post);
            $formComment = $this->get('form.factory')->create(CommentType::class, $comment);
            $listFormComment[$post->getId()] = $formComment->createView();
        }
        return $listFormComment;
    }

    private function createListScores($postList) {
        $listScores = [];
        $em = $this->getDoctrine()->getManager();
        $scoreRepository = $em->getRepository('NineGagBundle:Score');

        foreach ($postList as $post) {
            $result = $scoreRepository->getPostScore($post->getId());
            if(empty($result[0])){
                $postScore = 0;
            }else{
                $postScore = $result[0]['postScore'];
            }
            $listScores[$post->getId()] = $postScore;
        }
        return $listScores;
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
        //return $this->redirectToRoute('nine_gag_afficherPosts', ['formPost' => $form]);
        $postList = $this->getListPosts();
        $listFormComment = $this->createListFormComments($postList);
        return $this->render('NineGagBundle:Post:homepage.html.twig', [
                    'postList' => $this->getListPosts(),
                    'form' => $form->createView(),
                    'listFormComment' => $listFormComment
        ]);
    }

}
