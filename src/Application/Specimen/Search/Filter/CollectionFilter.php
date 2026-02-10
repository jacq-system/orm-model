<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class CollectionFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->collection === null) {
            return;
        }

        $qb
            ->andWhere('collection.id = :collection')
            ->setParameter('collection', $parameters->collection);
    }
}

