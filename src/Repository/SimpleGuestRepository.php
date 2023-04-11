<?php

namespace App\Repository;

use App\Entity\SimpleGuest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SimpleGuest>
 *
 * @method SimpleGuest|null find($id, $lockMode = null, $lockVersion = null)
 * @method SimpleGuest|null findOneBy(array $criteria, array $orderBy = null)
 * @method SimpleGuest[]    findAll()
 * @method SimpleGuest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimpleGuestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SimpleGuest::class);
    }

    public function save(SimpleGuest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SimpleGuest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SimpleGuest[] Returns an array of SimpleGuest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SimpleGuest
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
