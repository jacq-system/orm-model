<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class FamilyFilter implements SpecimenQueryFilter
{
        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->family === null) {
            return;
        }
        $joinManager->leftJoin($qb, 'specimen.species', 'species');
        $joinManager->leftJoin($qb, 'species.genus', 'genus');
        $joinManager->leftJoin($qb, 'genus.family', 'family');

        $qb
            ->andWhere($qb->expr()->orX(
                $qb->expr()->like('family.name', ':family'),
                $qb->expr()->like('family.nameAlternative', ':family')))
            ->setParameter('family', $parameters->family . '%');
    }
}

