<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class CollectionFilter implements SpecimenQueryFilter
{
        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->collection === null) {
            return;
        }

        $joinManager->leftJoin($qb, 'specimen.herbCollection','collection');
        $qb
            ->andWhere('collection.id = :collection')
            ->setParameter('collection', $parameters->collection);
    }
}

