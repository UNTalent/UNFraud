<?php

namespace App\Repository\DomainData;

use App\Entity\DomainData\DnsRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DnsRecord>
 *
 * @method DnsRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method DnsRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method DnsRecord[]    findAll()
 * @method DnsRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DnsRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DnsRecord::class);
    }

    public function save(DnsRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DnsRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSimilar(DnsRecord $record): ?DnsRecord {
        return $this->findOneBy([
            'recordType' => $record->getRecordType(),
            'value' => $record->getValue()
        ]);
    }

//    /**
//     * @return DnsRecord[] Returns an array of DnsRecord objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DnsRecord
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
