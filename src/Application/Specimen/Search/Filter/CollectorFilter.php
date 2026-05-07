<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use JACQ\Entity\Jacq\Herbarinput\Collector;
use JACQ\Entity\Jacq\Herbarinput\Collector2;
use JACQ\Repository\Herbarinput\CollectorRepository;
use JACQ\Repository\Herbarinput\Collector2Repository;


final class CollectorFilter implements SpecimenQueryFilter
{
    public function __construct(
        protected CollectorRepository $collectorRepository,
        protected Collector2Repository $collector2Repository
    )
    {
    }

        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->collector === null) {
            return;
        }

        $conditions = [];
        $collectors1 = $this->collectorRepository->findIdsByNamePrefix($parameters->collector);
        if (!empty($collectors1)) {
            $conditions[] = $qb->expr()->in('specimen.collector', $collectors1);
        }
        $collectors2 = $this->collector2Repository->findIdsByNamePrefix($parameters->collector);
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

