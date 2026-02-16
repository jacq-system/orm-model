<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Sort;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;

final class DefaultSpecimenSort implements SpecimenQuerySort
{
    public function apply(
        QueryBuilder              $qb,
        SpecimenSearchJoinManager $joinManager,
        SpecimenSearchParameters  $parameters
    ): void
    {

        $qb->resetDQLPart('orderBy');


        foreach ($parameters->sort as $column => $direction) {
            // normalize + validate direction
            $direction = strtoupper($direction);
            if (!in_array($direction, ['ASC', 'DESC'], true)) {
                $direction = 'ASC';
            }

            $mappedColumn = match ($column) {

                // sciname (scientific name)
                'sciname', 'materializedName.scientificName' => (function () use ($qb, $joinManager) {
                    $joinManager->leftJoin($qb,'specimen.species', 'species');
                    $joinManager->leftJoin($qb,'species.materializedName', 'materializedName');

                    return 'materializedName.scientificName';
                })(),

                // coll (collector(s))
                'coll','collector.name' => (function () use ($qb, $joinManager) {
                    $joinManager->leftJoin($qb,'specimen.collector', 'collector');
                    return 'collector.name';
                })(),

                // ser (series)
                'ser','series.name' => (function () use ($qb, $joinManager) {
                    $joinManager->leftJoin($qb,'specimen.series', 'series');
                    return 'series.name';
                })(),

                // num (collector number)
                'num','specimen.number' => 'specimen.number',

                // herbnr (herbarium number)
                    'herbnr','specimen.herbNumber' => 'specimen.herbNumber',

                default => null,
            };

            if ($mappedColumn === null) {
                continue;
            }

            $qb->addOrderBy($mappedColumn, $direction);
        }

        // compulsory sort to make it deterministic
        if (!array_key_exists('specimen.id', $parameters->sort)) {
            $qb->addOrderBy('specimen.id', 'ASC');
        }
    }
}
