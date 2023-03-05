<?php

namespace App\Repository;

use App\Entity\Jeux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\CategorieJeux;
/**
 * @extends ServiceEntityRepository<Jeux>
 *
 * @method Jeux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jeux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jeux[]    findAll()
 * @method Jeux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeuxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jeux::class);
    }

    public function save(Jeux $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Jeux $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Jeux[] Returns an array of Jeux objects
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

//    public function findOneBySomeField($value): ?Jeux
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByCategory($categoryName)
    {
        return $this->createQueryBuilder('j')
            ->join('j.Categories', 'c')
            ->where('c.NomCat = :categoryName')
            ->setParameter('categoryName', $categoryName)
            ->getQuery()
            ->getResult();
    }

    public function getJeuxSimilaires(CategorieJeux $category, Jeux $jeu)
    {
        return $this->createQueryBuilder('j')
            ->leftJoin('j.Categories', 'c')
            ->where('c.id = :categorie_id')
            ->andWhere('j.id != :jeux_id')
            ->setParameter('categorie_id', $category->getId())
            ->setParameter('jeux_id', $jeu->getId())
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function rechercherJeux($libelle = null, $categorie = null)
    {
        $qb = $this->createQueryBuilder('j')
            ->leftJoin('j.Categories', 'c');
    
        if ($libelle) {
            $qb->andWhere('j.libelle LIKE :libelle')
                ->setParameter('libelle', '%'.$libelle.'%');
        }
    
        if ($categorie) {
            $qb->andWhere('c.NomCat LIKE :categorie')
                ->setParameter('categorie', '%'.$categorie.'%');
        }
    
        return $qb->getQuery()->getResult();
    }
    
    

public function findByNoteMyonneDesc(): array
{
    return $this->createQueryBuilder('j')
        ->orderBy('j.noteMyonne', 'DESC')
        ->getQuery()
        ->getResult();
}

}