<?php

namespace App\Repository;

use App\Entity\DislikeBlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DislikeBlog>
 *
 * @method DislikeBlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method DislikeBlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method DislikeBlog[]    findAll()
 * @method DislikeBlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DislikeBlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DislikeBlog::class);
    }

    public function save(DislikeBlog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DislikeBlog $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function numberDislikesByBlog($id){
        $entityManager = $this -> getEntityManager();
        $query=$entityManager->createQuery('SELECT count(d.id) FROM APP\Entity\DislikeBlog d WHERE d.blog=:id')
        ->setParameter('id',$id);
        return $query->getSingleScalarResult();
    }

//    /**
//     * @return DislikeBlog[] Returns an array of DislikeBlog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DislikeBlog
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
