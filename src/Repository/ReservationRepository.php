<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }


    public function findByPackDate( $pack, \DateTime $date_debut, \DateTime $date_fin)
    {

        return $this->createQueryBuilder('r')
            ->where("r.date >= :debut")
            ->andWhere("r.date <= :fin")
            ->andWhere("r.pack = :pack" )
//            ->andWhere("r.date' = :date")
            ->setParameter('debut', $date_debut)
            ->setParameter('fin', $date_fin)
            ->setParameter('pack', $pack)
//            ->setParameter('date', $date)
           ->orderBy('r.date')
            ->getQuery()
            ->getResult();
    }

    public function findByResa($pack, \DateTime $date)
    {
        return $this->createQueryBuilder('r')
            ->where("r.date = :date")
            ->andWhere("r.pack = :pack")
            ->setParameter('date', $date)
            ->setParameter('pack', $pack)
            ->getQuery()
            ->getResult();
    }

    public function findResaUser($user)
    {
        return $this->createQueryBuilder('r')
            ->where("r.user = :user")
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
