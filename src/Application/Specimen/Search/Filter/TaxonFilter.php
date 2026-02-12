<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use JACQ\Service\SpeciesService;


final readonly class TaxonFilter implements SpecimenQueryFilter
{
    public function __construct(
        private   SpeciesService       $speciesService
    )
    {
    }

    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->taxon === null) {
            return;
        }

        $taxaIds = $this->getTaxaIds($parameters->taxon);
        $conditions = [];
        if (empty($taxaIds)) {
            $qb->andWhere('1 = 0');
        }

        //result includes NULL rows that need to be excluded
        $taxonId = array_filter(array_column($taxaIds, 'taxonID'), fn($value) => $value !== null);
        $basID = array_filter(array_column($taxaIds, 'basID'), fn($value) => $value !== null);
        $synID = array_filter(array_column($taxaIds, 'synID'), fn($value) => $value !== null);
        if (!empty($parameters->includeSynonym)) {
            if (!empty($taxonId)) {
                $conditions[] = $qb->expr()->orX(
                    $qb->expr()->in('species.id', $taxonId),
                    $qb->expr()->in('species.basionym', $taxonId),
                    $qb->expr()->in('species.validName', $taxonId)
                );
            }

            if (!empty($basID)) {
                $conditions[] = $qb->expr()->orX(
                    $qb->expr()->in('species.id', $basID),
                    $qb->expr()->in('species.basionym', $basID),
                    $qb->expr()->in('species.validName', $basID)
                );
            }

            if (!empty($synID)) {
                $conditions[] = $qb->expr()->orX(
                    $qb->expr()->in('species.id', $synID),
                    $qb->expr()->in('species.basionym', $synID),
                    $qb->expr()->in('species.validName', $synID)
                );
            }
        } else {
            if (!empty($taxonId)) {
                $conditions[] = $qb->expr()->orX(
                    $qb->expr()->in('species.id', $taxonId)
                );
            }
        }

        //finally add to the builder
        $qb
            ->andWhere(
                $qb->expr()->orX(...$conditions)
            );
    }

    protected function getTaxaIds(string $value): array
    {
        $taxonIDList = [];
        $names = explode(',', $value);
        foreach ($names as $name) {
            $taxa = $this->speciesService->fulltextSearch($name);

            foreach ($taxa as $taxon) {
                $taxonIDList[] = $taxon['taxonID'];
            }
        }
        return array_unique($taxonIDList);

    }
}

