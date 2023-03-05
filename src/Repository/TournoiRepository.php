<?php

namespace App\Repository;

use App\Entity\Tournoi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * @extends ServiceEntityRepository<Tournoi>
 *
 * @method Tournoi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournoi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournoi[]    findAll()
 * @method Tournoi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournoiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournoi::class);
    }

    public function save(Tournoi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tournoi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
   
    public function getTournoiSimilaire($type,$jeu)
    {
      return $this->createQueryBuilder('t')
        ->where('t.typeTournoi = :type')
        ->orWhere('t.jeu = :jeu')
        ->setParameter('type',$type)
        ->setParameter('jeu',$jeu)  
        ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }
    public function rechercherTournoi($nom,$jeu,$from,$to,/*$ordre*/)
    {   $nomExists=null;
        $jeuExists=null;
        if($nom!=null)
        {$nomExists= $this->createQueryBuilder('t')
        ->where('t.nomTournoi LIKE :nom')
        ->setParameter('nom','%'.$nom.'%')  
        ->getQuery()
        ->getResult()
        ;}  //checks existence of nom 
        if($jeu!=null)
        {
        $jeuExists= $this->createQueryBuilder('t')
        ->join('t.jeu', 'j')
        ->andWhere('j.ref LIKE :jeu')
        ->setParameter('jeu', '%'.$jeu.'%')  
        ->getQuery()
        ->getResult()
        ;//checks ithe jeu exists
    }

//var_dump($jeuExists);
//var_dump($nomExists);
        if(($nomExists==null&&$jeuExists!=null)||($nomExists==null && $jeuExists==null))//both  null
        {
            return $this->createQueryBuilder('t')
            ->where('t.nomTournoi LIKE :nom' )
            ->setParameter('nom', 'sjsklj')      
            ->getQuery()
            ->getResult();
            //return $this->findAll();
       /*  if($ordre=="alpha"){
            return $this->createQueryBuilder('t')
            ->orderBy('t.nomTournoi', 'ASC')    
            ->getQuery()
            ->getResult();
        }
            else if($ordre=="date")
            {
            return $this->createQueryBuilder('t')
            ->orderBy('t.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
            }*/
        }
      /*  else if(($nomExists!=null) && ($jeuExists==null))
        {
        /* if($ordre=="alpha"){
            return $this->createQueryBuilder('t')
            ->where('t.nomTournoi LIKE :nom')
            ->andWhere('t.dateDebut BETWEEN :startDate AND :endDate')
            ->setParameter('nom', '%'.$nom.'%')  
            ->setParameter('startDate', $from->format('Y-m-d'))
            ->setParameter('endDate', $to->format('Y-m-d'))
            ->orderBy('t.nomTournoi', 'ASC')    
            ->getQuery()
            ->getResult();
            
        }
            else if($ordre=="date")
            {
            return $this->createQueryBuilder('t')
        ->where('t.nomTournoi LIKE :nom')
        ->andWhere('t.dateDebut BETWEEN :startDate AND :endDate')
        ->setParameter('nom', '%'.$nom.'%')  
        ->setParameter('startDate', $from->format('Y-m-d'))
        ->setParameter('endDate', $to->format('Y-m-d'))
        ->orderBy('t.dateDebut', 'ASC')
        ->getQuery()
        ->getResult();
            }

            
           return $this->createQueryBuilder('t')
        ->where('t.nomTournoi LIKE :nom')
        ->andWhere('t.dateDebut BETWEEN :startDate AND :endDate')
        ->setParameter('nom', '%'.$nom.'%')  
        ->setParameter('startDate', $from->format('Y-m-d'))
        ->setParameter('endDate', $to->format('Y-m-d'))
        ->getQuery()
        ->getResult();
        }
*/        
        else if(($jeuExists!=null) && ($nomExists!=null)) {  
            /*if($ordre=="alpha"){
                return $this->createQueryBuilder('t')
                ->join('t.jeu', 'j')
                ->where('t.nomTournoi LIKE :nom')
                ->andWhere('j.nomJeu LIKE :jeu')
                ->andWhere('t.dateDebut BETWEEN :startDate AND :endDate')
                ->setParameter('nom', '%'.$nom.'%')  
                ->setParameter('jeu', '%'.$jeu.'%')  
                ->setParameter('startDate', $from->format('Y-m-d'))
                ->setParameter('endDate', $to->format('Y-m-d'))
                ->orderBy('t.nomTournoi', 'ASC')    
                ->getQuery()
                ->getResult();
               
            }
                else if($ordre=="date")
                {
                return $this->createQueryBuilder('t')
                ->join('t.jeu', 'j')
                ->where('t.nomTournoi LIKE :nom')
                ->andWhere('j.nomJeu LIKE :jeu')
                ->andWhere('t.dateDebut BETWEEN :startDate AND :endDate')
                ->setParameter('nom', '%'.$nom.'%')  
                ->setParameter('jeu', '%'.$jeu.'%')  
                ->setParameter('startDate', $from->format('Y-m-d'))
                ->setParameter('endDate', $to->format('Y-m-d'))
                ->orderBy('t.dateDebut', 'ASC')
                ->getQuery()
                ->getResult();
                
                }*/
       
             return $this->createQueryBuilder('t')
        ->join('t.jeu', 'j')
        ->where('t.nomTournoi LIKE :nom')
        ->andWhere('j.ref LIKE :jeu')
        ->andWhere('t.dateDebut BETWEEN :startDate AND :endDate')
        ->setParameter('nom', '%'.$nom.'%')  
        ->setParameter('jeu', '%'.$jeu.'%')  
        ->setParameter('startDate', $from->format('Y-m-d'))
        ->setParameter('endDate', $to->format('Y-m-d'))
        ->getQuery()
        ->getResult();
        }
    }
    public function statTournoi($jeu)
    {
         
            return $this->createQueryBuilder('t')
        ->select('t.participantTotal, COUNT(t.id) as count')
       ->groupBy('t.participantTotal')       
        ->getQuery()
        ->getResult();

    }
    
    public function inscriptionTournoi($jeu)
    {
            return $this->createQueryBuilder('t')
        ->select('t.participantTotal, COUNT(t.id) as count')
       ->groupBy('t.participantTotal')       
        ->getQuery()
        ->getResult();
    }
    
    public function ordonnerTournoi($ordre)
    {
        if($ordre=="alpha"){
        return $this->createQueryBuilder('t')
        ->orderBy('t.nomTournoi', 'ASC')
        ->getQuery()
        ->getResult();
        }
        else if($ordre=="date")
        {
            return $this->createQueryBuilder('t')
            ->orderBy('t.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
        }
        else if($ordre=="type")
        {
            return $this->createQueryBuilder('t')
            ->orderBy('t.typeTournoi', 'ASC')
            ->getQuery()
            ->getResult();
        }
    }

//    /**
//     * @return Tournoi[] Returns an array of Tournoi objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tournoi
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
