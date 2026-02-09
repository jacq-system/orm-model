<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class LocalityFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->locality === null) {
            return;
        }

        $qb
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('specimen.locality', ':locality'),
                    $qb->expr()->like('specimen.localityEng', ':locality')
                )
            )
            ->setParameter('locality', "%" . $parameters->locality . "%");
    }
}

