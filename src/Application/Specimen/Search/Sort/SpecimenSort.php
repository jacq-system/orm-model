<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Sort;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;

final class SpecimenSort implements SpecimenQuerySort
{

    /** @var array<string, callable(QueryBuilder, SpecimenSearchJoinManager, string): void> */
    private array $sortHandlers;

    public function __construct()
    {

        $this->sortHandlers = [

            SpecimenSortEnum::SCIENTIFIC_NAME->value => function (QueryBuilder $qb, SpecimenSearchJoinManager $jm, string $dir): void {
                $jm->leftJoin($qb, 'specimen.species', 'species');
                $jm->leftJoin($qb, 'species.materializedName', 'materializedName');

                $qb->addOrderBy('materializedName.scientificName', $dir);
            },

            SpecimenSortEnum::COLLECTOR->value => function (QueryBuilder $qb, SpecimenSearchJoinManager $jm, string $dir): void {
                $jm->leftJoin($qb, 'specimen.collector', 'collector');
                $qb->addOrderBy('collector.name', $dir);
            },

            SpecimenSortEnum::SERIES->value => function (QueryBuilder $qb, SpecimenSearchJoinManager $jm, string $dir): void {
                $jm->leftJoin($qb, 'specimen.series', 'series');
                $qb->addOrderBy('series.name', $dir);
            },

            SpecimenSortEnum::COLLECTOR_NUMBER->value => fn($qb, $jm, $dir) => $qb->addOrderBy('specimen.number', $dir),
            SpecimenSortEnum::HERB_NUMBER->value => fn($qb, $jm, $dir) => $qb->addOrderBy('specimen.herbNumber', $dir),
            SpecimenSortEnum::DATE->value => fn($qb, $jm, $dir) => $qb->addOrderBy('specimen.date', $dir),
            SpecimenSortEnum::LOCATION->value => function (QueryBuilder $qb, SpecimenSearchJoinManager $jm, string $dir): void {
                $jm->leftJoin($qb, 'specimen.country', 'country');
                $jm->leftJoin($qb, 'specimen.province', 'province');
                $qb->orderBy('country.nameEng', $dir)
                    ->addOrderBy('province.name', $dir)
                    ->addOrderBy('specimen.locality', $dir);
            },
            SpecimenSortEnum::TYPUS->value => function (QueryBuilder $qb, SpecimenSearchJoinManager $jm, string $dir): void {
                $jm->leftJoin($qb, 'specimen.typus', 'typus');
                $jm->leftJoin($qb, 'typus.rank', 'rank');
                $qb->orderBy('rank.latinName', $dir);
            },

            SpecimenSortEnum::COORDS->value => function (QueryBuilder $qb, SpecimenSearchJoinManager $jm, string $dir): void {
                $qb->addOrderBy('specimen.degreeS', $dir);
                $qb->addOrderBy('specimen.degreeE', $dir);
            },
        ];

        //aliases
        $this->sortHandlers['materializedName.scientificName'] = $this->sortHandlers['sciname'];
        $this->sortHandlers['collector.name'] = $this->sortHandlers['coll'];
        $this->sortHandlers['series.name'] = $this->sortHandlers['ser'];
        $this->sortHandlers['specimen.number'] = $this->sortHandlers['num'];
        $this->sortHandlers['specimen.herbNumber'] = $this->sortHandlers['herbnr'];

    }

    public function apply(
        QueryBuilder              $qb,
        SpecimenSearchJoinManager $joinManager,
        SpecimenSearchParameters  $parameters
    ): void
    {
        $qb->resetDQLPart('orderBy');

        foreach ($parameters->sort as $column => $direction) {
            $direction = strtoupper($direction);
            if (!in_array($direction, ['ASC', 'DESC'], true)) {
                $direction = 'ASC';
            }


            $handler = $this->sortHandlers[$column] ?? null;

            if ($handler === null) {
                continue;
            }

            $handler($qb, $joinManager, $direction);
        }

        // deterministic fallback
        if (!array_key_exists('specimen.id', $parameters->sort)) {
            $qb->addOrderBy('specimen.id', 'ASC');
        }
    }
}
