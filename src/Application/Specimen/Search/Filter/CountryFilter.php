<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class CountryFilter implements SpecimenQueryFilter
{
        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->country === null) {
            return;
        }

        $joinManager->leftJoin($qb, 'specimen.country', 'country');

        $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('country.name', ':country'),
                $qb->expr()->like('country.nameEng', ':country'),
                $qb->expr()->andX(
                    $qb->expr()->like('country.variants', ':country'),
                    $qb->expr()->notLike('country.variants', ':countryExcluded'),
                )
            ))
            ->setParameter('country', $parameters->country . '%')
            ->setParameter('countryExcluded', '%(%' . $parameters->country . '%)%');
    }
}

