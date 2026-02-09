<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class IsTypusFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->onlyType === null) {
            return;
        }

        $qb->innerJoin('specimen.typus', 'typus');
    }
}

