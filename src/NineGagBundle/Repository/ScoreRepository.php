<?php

namespace NineGagBundle\Repository;

/**
 * ScoreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScoreRepository extends \Doctrine\ORM\EntityRepository
{
     public function getPostScore($idPost) {
        $qb = $this->_em->createQueryBuilder()
                ->select('SUM(s.value) AS postScore')
                ->from('NineGagBundle:Post', 'p')
                ->from('NineGagBundle:Score', 's')
                ->where('p.id = s.post')
                ->andWhere('p.id = :idPost')
                ->setParameter('idPost', $idPost);

        return $qb->getQuery()->getResult();
    }
}