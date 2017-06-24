<?php

namespace NineGagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use NineGagBundle\Entity\Post;
use NineGagBundle\Form\PostType;
use NineGagBundle\Entity\Score;
use NineGagBundle\Form\CommentType;

class ScoreController extends Controller {

    public function editScoreAction(Request $request, int $idPost, int $valueToChange) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }
        $em = $this->getDoctrine()->getManager();
        $scoreRepository = $em->getRepository('NineGagBundle:Score');

        $score = $scoreRepository->findOneBy(['user' => $this->getUser()]);
        if ($score != null && $score->getValue() == $valueToChange) {
            return new JsonResponse(['message' => 'Vote déjà effectué'], 400);
        }

        $postRepository = $em->getRepository('NineGagBundle:Post');

        $post = $postRepository->findOneBy(['id' => $idPost]);
        if ($score == null) {
            $score = new Score();
        }
        $score->setValue($valueToChange);
        $score->setPost($post);
        $score->setUser($this->getUser());
        $em->persist($score);
        $em->flush();

        $postScore = $scoreRepository->getPostScore($idPost)[0]['postScore'];

        return new JsonResponse(['message' => 'Success!', 'newScore' => $postScore], 200);
    }

}
