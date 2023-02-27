<?php

namespace App\Repository;

use App\Entity\LikeBlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LikeBlog>
 *
 * @method LikeBlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method LikeBlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method LikeBlog[]    findAll()
 * @method LikeBlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeBlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LikeBlog::class);
    }

    public function save(LikeBlog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LikeBlog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function numberLikesByBlog($id){
        $entityManager = $this -> getEntityManager();
        $query=$entityManager->createQuery('SELECT count(l.id) FROM APP\Entity\LikeBlog l WHERE l.blog=:id')
        ->setParameter('id',$id);
        return $query->getSingleScalarResult();
    }

    public function findTopLikedBlogs()
    {
        
        return $this->createQueryBuilder('l')
            ->select('b.id, b.titre, b.etat , COUNT(l.id) as likeCount')
            ->join('l.blog', 'b')
            ->groupBy('b.id')
            ->orderBy('likeCount', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return LikeBlog[] Returns an array of LikeBlog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LikeBlog
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
