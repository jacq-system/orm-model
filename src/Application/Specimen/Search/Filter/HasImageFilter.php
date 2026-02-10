<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class HasImageFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->onlyImages === false) {
            return;
        }

        $qb
            ->andWhere($qb->expr()->orX(
                $qb->expr()->eq('specimen.image', 1),
                $qb->expr()->eq('specimen.imageObservation', 1)
            ));
    }
}

