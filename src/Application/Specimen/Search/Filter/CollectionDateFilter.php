<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class CollectionDateFilter implements SpecimenQueryFilter
{
        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->collectionDate === null) {
            return;
        }

        $qb
            ->andWhere('specimen.date LIKE :collectionDate')
            ->setParameter('collectionDate', '%' . $parameters->collectionDate . '%');
    }
}

