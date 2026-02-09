<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class ProvinceFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->province === null) {
            return;
        }

        $qb
            ->join('specimen.province', 'province')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->like('province.name', ':province'),
                $qb->expr()->like('province.nameLocal', ':province')
            ))
            ->setParameter('province', $parameters->province . '%');
    }
}

