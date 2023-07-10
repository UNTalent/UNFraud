<?php

namespace App\Repository\DomainData;

use App\Entity\Domain;
use App\Entity\DomainData\DomainDnsRecord;
use App\Entity\DomainData\DnsRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DomainDnsRecord>
 *
 * @method DomainDnsRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainDnsRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomainDnsRecord[]    findAll()
 * @method DomainDnsRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomainDnsRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomainDnsRecord::class);
    }

    public function save(DomainDnsRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DomainDnsRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByRecordAndDomain(DnsRecord $dnsRecord, Domain $domain): ?DomainDnsRecord
    {
        return $this->findOneBy([
            'dnsRecord' => $dnsRecord,
            'domain' => $domain,
        ]);
    }

//    /**
//     * @return DomainDnsRecord[] Returns an array of DomainDnsRecord objects
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

//    public function findOneBySomeField($value): ?DomainDnsRecord
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
