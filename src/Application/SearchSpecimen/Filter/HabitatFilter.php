<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class HabitatFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->habitat === null) {
            return;
        }

        $qb
            ->andWhere('specimen.habitat LIKE :habitat')
            ->setParameter('habitat', '%' . $parameters->habitat . '%');
    }
}

