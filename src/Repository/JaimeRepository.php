<?php

namespace App\Repository;

use App\Entity\Jaime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jaime>
 *
 * @method Jaime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jaime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jaime[]    findAll()
 * @method Jaime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JaimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jaime::class);
    }

    public function save(Jaime $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function numberLikesByEquipe($id){
        $entityManager = $this -> getEntityManager();
        $query=$entityManager->createQuery('SELECT count(l.id) FROM APP\Entity\Jaime l WHERE l.equipe=:id')
        ->setParameter('id',$id);
        return $query->getSingleScalarResult();
    }

    public function findTopLikedEquipes()
    {
        
        return $this->createQueryBuilder('l')
            ->select('e.id , e.nom_equipe , COUNT(l.id) as likeCount')
            ->join('l.equipe', 'e')
            ->groupBy('e.id')
            ->orderBy('likeCount', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function remove(Jaime $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Jaime[] Returns an array of Jaime objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Jaime
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}