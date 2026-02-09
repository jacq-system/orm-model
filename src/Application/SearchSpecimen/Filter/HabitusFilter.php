<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class HabitusFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->habitus === null) {
            return;
        }

        $qb
            ->andWhere('specimen.habitus LIKE :habitus')
            ->setParameter('habitus', '%' . $parameters->habitus . '%');
    }
}

