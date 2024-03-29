<?php

namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Equipe>
 *
 * @method Equipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipe[]    findAll()
 * @method Equipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    public function save(Equipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Equipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findEntitiesByString(string $str)
    {
        return $this->createQueryBuilder('e')
            ->where('e.title LIKE :str')
            ->setParameter('str', '%'.$str.'%')
            ->getQuery()
            ->getResult();
    }
/*
    public function search($query)
{
    $qb = $this->createQueryBuilder('e')
        ->where('e.nom_equipe LIKE :query')
        ->setParameter('query', '%'.$query.'%')
        ->orderBy('e.nom_equipe', 'ASC')
        ->setMaxResults(10);

    return $qb->getQuery()->getResult();
}
*/
    public function findAllOrderByNom(): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.nom_equipe', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function statnbjoueurs($equipe)
    {
         
            return $this->createQueryBuilder('e')
        ->select('e.nb_joueurs, COUNT(e.id) as count')
       ->groupBy('e .nb_joueurs')       
        ->getQuery()
        ->getResult();

    }



//    /**
//     * @return Equipe[] Returns an array of Equipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Equipe
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}