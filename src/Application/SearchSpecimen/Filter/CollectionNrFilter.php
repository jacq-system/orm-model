<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class CollectionNrFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->collectionNr === null) {
            return;
        }

        $qb
            ->andWhere('specimen.collectionNumber = :collectionNr')
            ->setParameter('collectionNr', $parameters->collectionNr);
    }
}

