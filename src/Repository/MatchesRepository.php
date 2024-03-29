<?php

namespace App\Repository;

use App\Entity\Matches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;
/**
 * @extends ServiceEntityRepository<Matches>
 *
 * @method Matches|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matches|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matches[]    findAll()
 * @method Matches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matches::class);
    }

    public function save(Matches $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Matches $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function getClassement($id)
    {
/*
        $rsm = new ResultSetMapping();
        // build rsm here
        $rsm->addEntityResult('App\Entity\Matches', 'm');
$rsm->addFieldResult('m', 'id', 'id');
$rsm->addFieldResult('m', 'scoreEquipeA', 'scoreEquipeA');
$rsm->addFieldResult('m', 'scoreEquipeB', 'scoreEquipeB');
$rsm->addFieldResult('m', 'scoreEquipeB', 'scoreEquipeB');
$rsm->addFieldResult('m', 'scoreEquipeB', 'scoreEquipeB');

        $query = $this->getEntityManager()->createNativeQuery('SELECT * FROM matches', $rsm);
        //$query->setParameter(1, 'romanb');
        
        $users = $query->getResult();
        return $users;*/

    $query1= $this->getEntityManager()->createQuery('SELECT e1.nom_equipe AS team,
    SUM(CASE WHEN m.scoreEquipeA > m.scoreEquipeB THEN 1 ELSE 0 END)  AS score
FROM App\Entity\Matches m
JOIN m.equipe1 e1
WHERE m.idTournoi = :id
GROUP BY team
ORDER BY score DESC
    ')
    ->setParameter('id',$id);
    ;


/*$query2= $this->getEntityManager()->createQuery(' SELECT e2.nomEquipe AS team,
SUM(CASE WHEN m.scoreEquipeA < m.scoreEquipeB THEN 1 ELSE 0 END)  AS score
FROM App\Entity\Matches m
JOIN m.equipe2 e2
GROUP BY team
ORDER BY score DESC
');*/
return $query1->getResult();
//Just call first team Winning Team
//I need to run a raw sql query
//Ill do this another week
/*$array=array_merge($query1->getResult(),$query2->getResult());    
//find solution for this 
return $array;
*/
}
    

//    /**
//     * @return Matches[] Returns an array of Matches objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Matches
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
