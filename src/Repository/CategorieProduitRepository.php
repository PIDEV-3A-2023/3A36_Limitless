<?php

namespace App\Repository;

use App\Entity\CategorieProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieProduit>
 *
 * @method CategorieProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieProduit[]    findAll()
 * @method CategorieProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieProduit::class);
    }

    public function save(CategorieProduit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategorieProduit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
     public function findCategoryByDate(): array
   {
       return $this->createQueryBuilder('c')          
            ->orderBy('c.dateCreation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return CategorieProduit[] Returns an array of CategorieProduit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategorieProduit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
