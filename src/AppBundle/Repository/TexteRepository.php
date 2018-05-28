<?php

namespace AppBundle\Repository;

/**
 * TexteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TexteRepository extends \Doctrine\ORM\EntityRepository
{
    public function getRandomEntity($exclude_id = null)
    {
        $qb = $this->createQueryBuilder('t')
            ->addSelect('RAND() as HIDDEN rand')
            ->addOrderBy('rand');
            
        if($exclude_id) {
            $qb->andWhere('t.id <> :id')
                ->setParameter('id', $exclude_id);
        }            
         
        return $qb->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }
    
    public function getSecondRandomEntity($auteur, $texte_id)
    {
        return  $this->createQueryBuilder('t')
            ->addSelect('RAND() as HIDDEN rand')
            ->addOrderBy('rand')
                ->where('t.auteur <> :auteur')
            ->setParameter('auteur', $auteur)
                ->andWhere('t.id <> :id')
                ->setParameter('id', $texte_id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
