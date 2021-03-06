<?php

namespace Shiny\AppBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends \Doctrine\ORM\EntityRepository
{
    public function getBooksWithCategorie()
    {
        $query = $this->createQueryBuilder('b');
        $query
            ->leftJoin('b.category', 'c')
            ->addSelect('c');

        return $query;
    }

    public function getBooksComplet()
    {
        $query = $this->getBooksWithCategorie()
            ->leftJoin('b.author', 'a')
            ->addSelect('a')
            ->getQuery();

        return $query->getResult();
    }

    public function getFromAuthor($id)
    {
        $query = $this->getBooksWithCategorie()
            ->leftJoin('b.author', 'a')
            ->addSelect('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $query->getResult();

    }

    public function findByParam($param, $id, $currentPage, $limite)
    {
        $query = $this->getBooksWithCategorie()
            ->leftJoin('b.author', 'a')
            ->addSelect('a');

        if ($param !== 'prof') {
            $query->where('c.id = :id')
            ->setParameter('id', $id);
        }
        else {
            $query
            ->where('a.id = :id')
            ->setParameter('id', $id);
        }
        $query
        ->setFirstResult(($currentPage -1) * $limite)
        ->setMaxResults($limite);

        return new Paginator($query, true);
    }

    public function findAllBooksWithPaginate($currentPage, $limite)
    {
        $query = $this->getBooksWithCategorie()
            ->leftJoin('b.author', 'a')
            ->addSelect('a')
            ->setFirstResult(($currentPage -1) * $limite)
            ->setMaxResults($limite);

        return new Paginator($query, true);
    }

    public function getLastBooks($limite)
    {
        $query = $this->getBooksWithCategorie()
            ->orderBy('b.id', 'DESC')
            ->setMaxResults($limite)
            ->getQuery();

        return $query->getResult();
    }

    public function getSearch($search, $author = null, $category = null)
    {
        // explose la chaine de caractère search pour un multiple recherche
        $terms = explode(' ', $search);
        $sql = 'b.title LIKE :search 
                OR c.name LIKE :search 
                OR a.firstName LIKE :search 
                OR a.lastName LIKE :search 
                OR a.nameComplet LIKE :search
                OR b.yearBook LIKE :search  
                OR b.content LIKE :search ';

        // ajoute la jointure sur l'entité prof et remplace le parameter search
        $query = $this->getBooksWithCategorie()
            ->leftJoin('b.author', 'a')
            ->addSelect('a')
            ->where($sql)
            ->setParameter('search', '%'.$terms[0].'%');

        if(count($terms) > 1) {
            for ($i = 1; $i < count($terms); $i++) {
                $query->andWhere($sql)->setParameter('search', '%'.$terms[$i].'%');
            }
        }

        if (null !== $author) {
            $query->andWhere('a.nameComplet = :author')->setParameter('author', $author);
        }

        if (null !== $category) {
            $query->andWhere('c.name = :category')->setParameter('category', $category);
        }

        return $query;
    }

    // Affiche la pagination avec le mot clé de search, author et/ou category
    public function getSearchWithPaginate($search, $author, $category, $currentPage, $limite)
    {
        $query = $this->getSearch($search, $author, $category)
            ->orderBy('b.updatedAt', 'DESC')
            ->setFirstResult(($currentPage -1) * $limite)
            ->setMaxResults($limite);
        return new Paginator($query, true);
    }

    public function findBySearch($search)
    {
        $query = $this->getSearch($search)
            ->orderBy('b.updatedAt', 'DESC')
            ->getQuery();

        return $query->getResult();
    }
}
