<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 *
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function save(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Blog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findDESC(){
        return $this->findBy(array(), array('id' => 'DESC'));
    }

    public function findMostRecentBlogs(){
        
        return $this->createQueryBuilder('b')
            ->andWhere('b.etat = :etat')
            ->setParameter('etat', 2)
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function findByTitleOrderedByDate($titre){

        $qb = $this->createQueryBuilder('b')
            ->where('b.titre LIKE :titre')
            ->setParameter('titre', '%' . $titre . '%')
            ->orderBy('b.id', 'DESC')
            ->getQuery();

        return $qb->getResult();
    }

    public function findMostUsedTags(){
        $qb = $this->createQueryBuilder('b')
            ->join('b.tags', 't')
            ->select('t.name, COUNT(b.id) as count')
            ->groupBy('t.id')
            ->orderBy('count', 'DESC')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    public function FindBlogByName($name) {
        $qb = $this->createQueryBuilder('b');
        $qb->where($qb->expr()->like('b.titre', ':name'))
           ->setParameter('name', '%'.$name.'%')
           ->orderBy('b.id', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
