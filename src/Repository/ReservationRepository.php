<?php

namespace App\Repository;

use App\Entity\Reservation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
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

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param DateTime $date
     * @return mixed
     */
    public function findWeek(DateTime $date): mixed
    {
        $start = $date->format('Y-m-d');
        $end = $date->modify('+6 days')->format('Y-m-d');

        return $this->createQueryBuilder('r')
            ->andWhere('r.date BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('r.date', 'ASC')
            ->leftJoin('r.simpleUser', 'u')
            ->addSelect('u')
            ->leftJoin('u.simpleGuests', 'g')
            ->addSelect('g')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DateTime $date
     * @return mixed
     */
    public function findDay(DateTime $date): mixed
    {
        $dayToFound = $date->format('Y-m-d');

        return $this->createQueryBuilder('r')
            ->andWhere('r.date = :day')
            ->setParameter('day', $dayToFound)
            ->leftJoin('r.simpleUser', 'u')
            ->addSelect('u')
            ->leftJoin('u.simpleGuests', 'g')
            ->addSelect('g')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param DateTime $date
     * @param $noon
     * @return float|int|mixed|string
     */
    public function findService(DateTime $date, $noon): mixed
    {
        $dayToFound = $date->format('Y-m-d');

        return $this->createQueryBuilder('r')
            ->andWhere('r.date = :day')
            ->andWhere('r.noonService = :noonService')
            ->setParameter('day', $dayToFound)
            ->setParameter('noonService', $noon)
            ->leftJoin('r.simpleUser', 'u')
            ->addSelect('u')
            ->leftJoin('u.simpleGuests', 'g')
            ->addSelect('g')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findById(int $id): mixed
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('r.simpleUser', 'u')
            ->addSelect('u')
            ->leftJoin('u.simpleGuests', 'g')
            ->addSelect('g')
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findRestaurant()
    {
    }
}
