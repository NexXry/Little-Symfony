<?php

namespace App\Repository;

use App\Entity\TshirtSizes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TshirtSizes>
 *
 * @method TshirtSizes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TshirtSizes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TshirtSizes[]    findAll()
 * @method TshirtSizes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TshirtSizesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TshirtSizes::class);
    }

    public function add(TshirtSizes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TshirtSizes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TshirtSizes[] Returns an array of TshirtSizes objects
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

//    public function findOneBySomeField($value): ?TshirtSizes
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
