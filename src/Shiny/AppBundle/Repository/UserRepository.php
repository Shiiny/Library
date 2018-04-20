<?php

namespace Shiny\AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLastRegister($limite)
    {
        $query = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults($limite)
            ->getQuery();

        return $query->getResult();
    }
}
