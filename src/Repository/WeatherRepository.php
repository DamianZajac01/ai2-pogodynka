<?php

namespace App\Repository;

use App\Entity\Localization;
use App\Entity\Weather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Weather>
 */
class WeatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weather::class);
    }

    public function findByLocalization(Localization $localization) {
        $queryBuilder = $this->createQueryBuilder('weather');
        $queryBuilder->where('weather.localization = :localization')
            ->setParameter('localization', $localization)
            ->andWhere('weather.date > :now')
            ->setParameter('now', date('Y-m-d'));

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

//    /**
//     * @return Weather[] Returns an array of Weather objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Weather
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
