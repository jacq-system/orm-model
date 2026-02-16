<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search;
final readonly class SpecimenSearchParameters
{
    public function __construct(
        public ?int    $institution = null,
        public ?string $institutionCode = null,
        public ?string $herbNr = null,
        public ?int    $collection = null,
        public ?string $collectorNr = null,
        public ?string $collector = null,
        public ?string $collectionDate = null,
        public ?string $collectionNr = null,
        public ?string $series = null,
        public ?string $locality = null,
        public ?string $habitus = null,
        public ?string $habitat = null,
        public ?string $taxonAlternative = null,
        public ?string $annotation = null,
        public ?string $country = null,
        public ?string $province = null,
        public bool    $onlyType = false,
        public bool    $includeSynonym = false,
        public bool    $onlyImages = false,
        public ?string $family = null,
        public bool    $onlyCoords = false,
        public ?string $taxon = null,
        public array   $sort = []
    )
    {
    }
}
