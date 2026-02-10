<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class FamilyFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->family === null) {
            return;
        }

        $qb
            ->join('genus.family', 'family');

        $qb
            ->andWhere($qb->expr()->orX(
                $qb->expr()->like('family.name', ':family'),
                $qb->expr()->like('family.nameAlternative', ':family')))
            ->setParameter('family', $parameters->family . '%');
    }
}

