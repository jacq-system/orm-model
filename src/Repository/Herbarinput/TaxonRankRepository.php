<?php declare(strict_types=1);

namespace JACQ\Repository\Herbarinput;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JACQ\Entity\Jacq\Herbarinput\TaxonRank;


class TaxonRankRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxonRank::class);
    }

    public function getRankHierarchies(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.name, r.hierarchy')
            ->orderBy('r.hierarchy')
            ->getQuery()->getResult();
    }


}
