<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class CollectorNrFilter implements SpecimenQueryFilter
{
        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->collectorNr === null) {
            return;
        }

        $conditions = [];

        if (ctype_digit($parameters->collectorNr)) {
            $conditions[] = $qb->expr()->eq('specimen.number', ':collectorNr');
            $qb->setParameter('collectorNr', (int)$parameters->collectorNr);
        }

        $likeParameter = "%" . $parameters->collectorNr . "%";
        $conditions[] = $qb->expr()->like('specimen.altNumber', ':collectorNrLike');
        $conditions[] = $qb->expr()->like('specimen.seriesNumber', ':collectorNrLike');

        $qb
            ->andWhere($qb->expr()->orX(...$conditions))
            ->setParameter('collectorNrLike', $likeParameter);

    }
}

