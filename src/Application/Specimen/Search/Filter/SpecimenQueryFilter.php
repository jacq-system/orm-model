<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;

interface SpecimenQueryFilter
{
    public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void;
}