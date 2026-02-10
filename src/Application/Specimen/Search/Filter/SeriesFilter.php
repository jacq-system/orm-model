<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class SeriesFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->series === null) {
            return;
        }

        $qb
            ->join('specimen.series', 'series')
            ->andWhere('series.name LIKE :series')
            ->setParameter('series', '%' . $parameters->series . '%');

    }
}

