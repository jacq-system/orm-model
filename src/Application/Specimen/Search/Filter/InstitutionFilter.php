<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class InstitutionFilter implements SpecimenQueryFilter
{
        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->institution === null) {
            return;
        }
        $joinManager->leftJoin($qb, 'specimen.herbCollection','collection');
        $joinManager->leftJoin($qb, 'collection.institution','institution');
        $qb
            ->andWhere('institution.id = :institution')
            ->setParameter('institution', $parameters->institution);
    }
}

