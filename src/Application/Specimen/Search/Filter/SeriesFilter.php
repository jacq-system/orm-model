<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class SeriesFilter implements SpecimenQueryFilter
{
        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->series === null) {
            return;
        }

        $joinManager->leftJoin($qb, 'specimen.series','series');
        $qb
            ->andWhere('series.name LIKE :series')
            ->setParameter('series', '%' . $parameters->series . '%');

    }
}

