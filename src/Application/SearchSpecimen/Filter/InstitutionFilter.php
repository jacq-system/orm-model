<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class InstitutionFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->institution === null) {
            return;
        }

        $qb
            ->join('collection.institution', 'institution')
            ->andWhere('institution.id = :institution')
            ->setParameter('institution', $parameters->institution);
    }
}

