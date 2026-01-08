<?php declare(strict_types=1);

namespace JACQ\Repository\Herbarinput;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use JACQ\Entity\Jacq\Herbarinput\Collector;


class CollectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collector::class);
    }

    public function getBloodhoundId(Collector $collector): ?string
    {
        try {
            return $this->createQueryBuilder('p')
                ->select('p.bloodHoundId')
                ->where('p.bloodHoundId LIKE :name')
                ->andWhere('p.id = :collector')
                ->setParameter('name', 'h%')
                ->setParameter('collector', $collector->id)
                ->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return null;
        }

    }

    public function iterateAll(): iterable
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c.id, c.name');

        $iterable = $qb->getQuery()->toIterable();

        foreach ($iterable as $row) {
            yield $row;
        }
    }

}
