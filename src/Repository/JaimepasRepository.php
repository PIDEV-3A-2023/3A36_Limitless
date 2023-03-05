<?php

namespace App\Repository;

use App\Entity\Jaimepas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jaimepas>
 *
 * @method Jaimepas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jaimepas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jaimepas[]    findAll()
 * @method Jaimepas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JaimepasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jaimepas::class);
    }

    public function save(Jaimepas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Jaimepas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function numberDislikesByEquipe($id){
        $entityManager = $this -> getEntityManager();
        $query=$entityManager->createQuery('SELECT count(e.id) FROM APP\Entity\Jaimepas e WHERE e.equipe=:id')
        ->setParameter('id',$id);
        return $query->getSingleScalarResult();
    }

//    /**
//     * @return Jaimepas[] Returns an array of Jaimepas objects
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

//    public function findOneBySomeField($value): ?Jaimepas
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}