<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class CollectionDateFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->collectionDate === null) {
            return;
        }

        $qb
            ->andWhere('specimen.date LIKE :collectionDate')
            ->setParameter('collectionDate', '%' . $parameters->collectionDate . '%');
    }
}

