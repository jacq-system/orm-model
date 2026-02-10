<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class HasCoordsFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->onlyCoords === false) {
            return;
        }

        $qb->andWhere($qb->expr()->orX(
            'specimen.degreeS IS NOT NULL',
            'specimen.degreeN IS NOT NULL'
        )
        );
    }
}

