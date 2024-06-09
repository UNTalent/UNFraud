<?php

namespace App\Repository\Complaint;

use App\Entity\Complaint\ComplaintReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComplaintReport>
 *
 * @method ComplaintReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintReport[]    findAll()
 * @method ComplaintReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplaintReport::class);
    }

    public function save(ComplaintReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ComplaintReport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ComplaintReport[] Returns an array of ComplaintReport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ComplaintReport
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
