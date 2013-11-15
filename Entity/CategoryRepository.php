<?php

namespace Teo\ProductBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function findCategoryByTag($tag)
    {
        $q = $this->getQueryForTag($tag);
        return $q->getQuery()->getOneOrNullResult();
    }

    public function findCategoriesByTag($tag)
    {
        $q = $this->getQueryForTag($tag);
        return $q->getQuery()->getResult();
    }

    public function getQueryForTag($tag)
    {
        $q = $this->createQueryBuilder('c');
        $q
            ->innerJoin('c.tags', 't')
            ->andWhere('t.name = :name')
            ->setParameter('name', $tag)
            ;
        return $q;
    }
}
