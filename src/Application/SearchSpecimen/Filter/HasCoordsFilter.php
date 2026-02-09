<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class HasCoordsFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->onlyCoords === null) {
            return;
        }

        $qb->andWhere($qb->expr()->orX(
            'specimen.degreeS IS NOT NULL',
            'specimen.degreeN IS NOT NULL'
        )
        );
    }
}

