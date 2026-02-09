<?php declare(strict_types=1);

namespace JACQ\Application\SearchSpecimen;
final class SpecimenSearchParameters
{
    public function __construct(
        public readonly ?int    $institution = null,
        public readonly ?string $herbNr = null,
        public readonly ?int    $collection = null,
        public readonly ?string $collectorNr = null,
        public readonly ?string $collector = null,
        public readonly ?string $collectionDate = null,
        public readonly ?string $collectionNr = null,
        public readonly ?string $series = null,
        public readonly ?string $locality = null,
        public readonly ?string $habitus = null,
        public readonly ?string $habitat = null,
        public readonly ?string $taxonAlternative = null,
        public readonly ?string $annotation = null,
        public readonly ?string $country = null,
        public readonly ?string $province = null,
        public readonly bool    $onlyType = false,
        public readonly bool    $includeSynonym = false,
        public readonly bool    $onlyImages = false,
        public readonly ?string $family = null,
        public readonly bool    $onlyCoords = false,
        public readonly ?string $taxon = null,
    )
    {
    }
}
