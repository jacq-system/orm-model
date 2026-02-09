<?php declare(strict_types=1);

namespace JACQ\Repository\Herbarinput;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JACQ\Entity\Jacq\Herbarinput\Collector;


class Collector2Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collector::class);
    }


    /**
     * @return int[]
     */
    public function findIdsByNamePrefix(string $prefix): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id')
            ->andWhere('c.name LIKE :value')
            ->setParameter('value', '%' . $prefix . '%')
            ->getQuery()
            ->getSingleColumnResult();
    }

}
