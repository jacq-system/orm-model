<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;
use JACQ\Entity\Jacq\Herbarinput\Collector;
use JACQ\Entity\Jacq\Herbarinput\Collector2;


final class CollectorFilter implements SpecimenQueryFilter
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->collector === null) {
            return;
        }

        $conditions = [];
        $collectors1 = $this->em->getRepository(Collector::class)->findIdsByNamePrefix($parameters->collector);
        if (!empty($collectors1)) {
            $conditions[] = $qb->expr()->in('specimen.collector', $collectors1);
        }
        $collectors2 = $this->em->getRepository(Collector2::class)->findIdsByNamePrefix($parameters->collector);
        if (!empty($collectors2)) {
            $conditions[] = $qb->expr()->in('specimen.collector2', $collectors2);
        }
        if ($conditions === []) {
            $qb->andWhere('1 = 0');
            return;
        }

        $qb->andWhere(
            $qb->expr()->orX(...$conditions)
        );

    }

}

