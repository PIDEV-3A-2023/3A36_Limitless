<?php

namespace App\Repository;
use App\Extensions\Doctrine\MatchAgainst;
use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Joueur>
 *
 * @method Joueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joueur[]    findAll()
 * @method Joueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joueur::class);
    }

    public function save(Joueur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Joueur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAllAdmins()
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.roles = :role')
            ->setParameter('role', '["ROLE_ADMIN"]')
            ->getQuery()
            ->getResult();
    }
    public function findAllUsers()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles NOT LIKE :role1')
            ->andWhere('u.roles NOT LIKE :role2')
            ->setParameter('role1', '%["ROLE_ADMIN"]%')
            ->setParameter('role2', '%["ROLE_SUPER_ADMIN"]%')
            ->getQuery()
            ->getResult();
    }
    public function findByNom(string $nom)
    {
        return $this->createQueryBuilder('u')
            ->where('u.nom LIKE :nom')
            ->setParameter('nom', '%' . $nom . '%')
            ->getQuery()
            ->getResult();
    }

    public function findByEmail(string $email)
    {
        return $this->createQueryBuilder('u')
            ->where('u.email LIKE :email')
            ->setParameter('email', '%' . $email . '%')
            ->getQuery()
            ->getResult();
    }

    public function findByIGN(string $ign)
    {
        return $this->createQueryBuilder('u')
            ->where('u.ign LIKE :ign')
            ->setParameter('ign', '%' . $ign . '%')
            ->getQuery()
            ->getResult();
    }
    public function findAllOrderByNom(): array
{
    return $this->createQueryBuilder('u')
        ->orderBy('u.nom', 'ASC')
        ->getQuery()
        ->getResult();
}
public function countVerifiedUsers(): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->where('u.isVerified = 1')
        ->getQuery()
        ->getSingleScalarResult();
}
public function countBannedUsers(): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.id)')
        ->where('u.is_banned = 1')
        ->getQuery()
        ->getSingleScalarResult();
}
public function findTopThreeWinners(): array
{
    return $this->createQueryBuilder('u')
        ->orderBy('u.wins', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
}
public function findTop7Winners(): array
{
    return $this->createQueryBuilder('u')
        ->orderBy('u.wins', 'DESC')
        ->setMaxResults(7)
        ->getQuery()
        ->getResult();
}
public function findTop7Losers(): array
{
    return $this->createQueryBuilder('u')
        ->orderBy('u.loses', 'DESC')
        ->setMaxResults(7)
        ->getQuery()
        ->getResult();
}
public function getUserWithMostWins(): array
{
    $qb = $this->createQueryBuilder('u');
    $qb->select('u.ign as ign', 'MAX(u.wins) as max_wins')
        ->groupBy('u.ign')
        ->orderBy('max_wins', 'DESC')
        ->setMaxResults(1);

    return $qb->getQuery()->getOneOrNullResult();
}

public function countUsersCreatedLast30Days(): int
{
    $qb = $this->createQueryBuilder('u');
    $qb->select('COUNT(u.id)')
        ->where('u.created_at >= :date')
        ->setParameter('date', new \DateTime('-30 days'));
    $query = $qb->getQuery();
    return (int) $query->getSingleScalarResult();
}

public function search($mots){
    $query = $this->createQueryBuilder('a');
    //$query->where('a.active = 1');
    if($mots != null){
        $query->where('MATCH_AGAINST(a.nom, a.prenom, a.email, a.ign) AGAINST (:mots boolean)>0')
            ->setParameter('mots', $mots);
    }
    return $query->getQuery()->getResult();
}

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Joueur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    //    /**
    //     * @return Joueur[] Returns an array of Joueur objects
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

    //    public function findOneBySomeField($value): ?Joueur
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
