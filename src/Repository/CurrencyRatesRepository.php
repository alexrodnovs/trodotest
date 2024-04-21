<?php

namespace App\Repository;

use App\Entity\CurrencyRates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyRates>
 *
 * @method CurrencyRates|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyRates|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyRates[]    findAll()
 * @method CurrencyRates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyRates::class);
    }

    public function findCurrencies(): ?array
    {
        return $this->createQueryBuilder('cr')
            ->select('cr.currencyTo')
            ->distinct()
            ->getQuery()
            ->getSingleColumnResult()
        ;
    }

    public function findFirstCurrencies(): ?array
    {
        $qb = $this->createQueryBuilder('cr');

        $subQuery = $this->createQueryBuilder('cr2')
            ->select('MIN(cr2.date)')
            ->getQuery()
            ->getSingleScalarResult();

        return $qb
            ->where($qb->expr()->eq('cr.date', ':min_date'))
            ->setParameter('min_date', new \DateTime($subQuery))
            ->getQuery()
            ->getResult()
        ;
    }

    public function getRatesQuery(array $params): Query
    {
        $qb = $this->createQueryBuilder('cr');

        $qb
            ->select('cr.rate, cr.date')
            ->where($qb->expr()->eq('cr.currencyFrom', ':base'))
            ->andWhere($qb->expr()->eq('cr.currencyTo', ':to'))
            ->setParameter('base', $params['base'])
            ->setParameter('to', $params['to'])
        ;

        $qb->orderBy('cr.' . $params['sortBy'], $params['sortOrder']);

        return $qb->getQuery();
    }

    public function findMinMaxAvgRates(array $params): array
    {
        $qb = $this->createQueryBuilder('cr');

        $qb
            ->select('MIN(cr.rate) AS min_rate, MAX(cr.rate) AS max_rate, AVG(cr.rate) AS avg_rate')
            ->where($qb->expr()->eq('cr.currencyFrom', ':base'))
            ->andWhere($qb->expr()->eq('cr.currencyTo', ':to'))
            ->setParameter('base', $params['base'])
            ->setParameter('to', $params['to'])
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
