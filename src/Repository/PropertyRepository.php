<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private LoggerInterface $logger)
    {
        parent::__construct($registry, Property::class);
    }

    public function list(int $page = 1, int $limit = 10, string $status = null): Paginator
    {
        $builder = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if ($status != null) {
            $builder->andWhere('p.status = :status')
                ->setParameter('status', $status);
        }

        return new Paginator($builder->getQuery());
    }

    public function save(Property $property): void
    {
        try {
            $this->getEntityManager()->persist($property);

            $this->getEntityManager()->flush();
        } catch (\Throwable $th) {
            $this->logger->error('Saving Property Error', ['exception' => $th]);

            throw $th;
        }
    }

    public function remove(Property $property): void
    {
        try {
            $this->getEntityManager()->remove($property);

            $this->getEntityManager()->flush();
        } catch (\Throwable $th) {
            $this->logger->error('Deleting Property Error', ['exception' => $th]);

            throw $th;
        }
    }

    //    /**
//     * @return Property[] Returns an array of Property objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Property
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
