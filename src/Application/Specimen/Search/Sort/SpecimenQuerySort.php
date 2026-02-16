<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Sort;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;

interface SpecimenQuerySort
{
    public function apply(
        QueryBuilder              $qb,
        SpecimenSearchJoinManager $joinManager,
        SpecimenSearchParameters  $parameters
    ): void;
}
