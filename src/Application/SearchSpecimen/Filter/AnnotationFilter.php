<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class AnnotationFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->annotation === null) {
            return;
        }

        $qb
            ->andWhere('specimen.annotation LIKE :annotation')
            ->setParameter('annotation', '%' . $parameters->annotation . '%');
    }
}

