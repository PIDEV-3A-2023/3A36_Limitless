<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 *
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    public function save(Commentaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commentaire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function FindCommentaireByIdBlog($id) {
        return $this->createQueryBuilder('c')
                    ->join('c.blog','b')
                    ->addSelect('b')
                    ->where('b.id=:id')
                    ->setParameter('id',$id)
                    ->getQuery()
                    ->getResult();
    }

    public function numberCommentsByBlog($id){
        $entityManager = $this -> getEntityManager();
        $query=$entityManager->createQuery('SELECT count(c.id) FROM APP\Entity\Commentaire c WHERE c.blog=:id')
        ->setParameter('id',$id);
        return $query->getSingleScalarResult();
    }
}
