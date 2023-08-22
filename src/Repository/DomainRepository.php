<?php

namespace App\Repository;

use App\Entity\Domain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Domain>
 *
 * @method Domain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Domain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Domain[]    findAll()
 * @method Domain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Domain::class);
    }

    public function add(Domain $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Domain $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function getActiveQueryBuilder()
    {
        return $this->createQueryBuilder('domain')
            ->join('domain.analysis', 'analysis')->addSelect('analysis')
            ->join('analysis.rating', 'rating')->addSelect('rating');
    }

    public function findSimilar(Domain $domain)
    {
        $existing = $this->findOneBy([
            'host' => $domain->getHost()
        ]);
        if (!$existing) {
            $this->add($domain);
            return $domain;
        }
        return $existing;
    }

    public function findOneByHost($host): ?Domain
    {
        return $this->findOneBy([
            'host' => $host
        ]);
    }

    public function findActive()
    {
        return $this->getActiveQueryBuilder()
            ->getQuery()->getResult();
    }

    public function findRecent()
    {
        return $this->getActiveQueryBuilder()
            ->orderBy('domain.reportCount', 'DESC')
            ->getQuery()->getResult();
    }

    public function findRecentDangerous()
    {
        $qb = $this->getActiveQueryBuilder()
            ->join('domain.reports', 'report')
            ->where('rating.isDangerous = TRUE')
            ->orderBy('report.createdAt', 'DESC')
            ->setMaxResults(8)
        ;

        return new Paginator($qb->getQuery());
    }

    public function findDangerous()
    {
        $qb = $this->getActiveQueryBuilder()
            ->join('domain.reports', 'report')
            ->where('rating.isDangerous = TRUE')
            ->orderBy('report.createdAt', 'DESC')
        ;

        return new Paginator($qb->getQuery());
    }

    public function findToInvestigate()
    {
        return $this->createQueryBuilder('domain')
            ->where('domain.lastCheckAt IS NULL')
            ->leftJoin('domain.analysis', 'analysis')->addSelect('analysis')
            ->leftJoin('analysis.rating', 'rating')->addSelect('rating')
            //->andWhere('rating.isDangerous = TRUE')
            ->getQuery()->getResult();
    }

    public function findToAnalyse() {
        return $this->createQueryBuilder('domain')
            ->andWhere('domain.analysis IS NULL')
            ->leftJoin('domain.reports', 'report')->addSelect('report')

            ->orderBy('report.createdAt', 'DESC')

            ->getQuery()->getResult();
    }



//    /**
//     * @return Domain[] Returns an array of Domain objects
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

//    public function findOneBySomeField($value): ?Domain
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
