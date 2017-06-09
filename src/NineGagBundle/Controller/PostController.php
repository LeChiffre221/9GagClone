<?php

namespace NineGagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use NineGagBundle\Entity\Post;
use NineGagBundle\Form\PostType;

class PostController extends Controller {

    public function afficherPostsAction() {

        $form = $this->get('form.factory')->create(PostType::class, new Post());

        return $this->render('NineGagBundle:Post:homepage.html.twig', [
                    'postList' => $this->getListPosts(),
                    'form' => $form->createView()
        ]);
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

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

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
            return new JsonResponse([
                'message' => 'Success!',
                'result' => $this->renderView('AppBundle:Default:accueil.html.twig', [
                    'postList' => $this->getListPosts(),
                    'form' => $form->createView()
                ])], 200);
        }
    }

}
