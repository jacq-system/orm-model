<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;


final class IsTypusFilter implements SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->onlyType === null) {
            return;
        }
        $qb->where(
            $qb->expr()->exists(
                'SELECT t.id FROM JACQ\Entity\Jacq\Herbarinput\Typus t WHERE t.specimen = specimen.id'
            )
        );
//        $qb->innerJoin('specimen.typus', 'typus');
    }
}

