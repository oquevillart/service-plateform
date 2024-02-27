<?php

namespace App\Repository;

use App\Entity\ShortenedUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShortenedUrl>
 *
 * @method ShortenedUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortenedUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortenedUrl[]    findAll()
 * @method ShortenedUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortenedUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortenedUrl::class);
    }

    //    /**
    //     * @return ShortenedUrl[] Returns an array of ShortenedUrl objects
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

    //    public function findOneBySomeField($value): ?ShortenedUrl
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
