<?php declare(strict_types=1);

namespace JACQ\UI\Http;

use JACQ\Application\Specimen\Search\SpecimenSearchParameters;

final class SpecimenSearchParametersFromSessionFactory
{
    public function __construct(
        private SearchFormSessionService $sessionService
    )
    {
    }

    public function create(): SpecimenSearchParameters
    {
        return new SpecimenSearchParameters(
            institution: (int) $this->sessionService->getFilter('institution') ?: NULL,
            herbNr: $this->sessionService->getFilter('herbNr') ?: NULL,
            collection: (int) $this->sessionService->getFilter('collection') ?: NULL,
            collectorNr: $this->sessionService->getFilter('collectorNr') ?: NULL,
            collector: $this->sessionService->getFilter('collector') ?: NULL,
            collectionDate: $this->sessionService->getFilter('collectionDate') ?: NULL,
            collectionNr: $this->sessionService->getFilter('collectionNr') ?: NULL,
            series: $this->sessionService->getFilter('series') ?: NULL,
            locality: $this->sessionService->getFilter('locality') ?: NULL,
            habitus: $this->sessionService->getFilter('habitus') ?: NULL,
            habitat: $this->sessionService->getFilter('habitat') ?: NULL,
            taxonAlternative: $this->sessionService->getFilter('taxonAlternative') ?: NULL,
            annotation: $this->sessionService->getFilter('annotation') ?: NULL,
            country: $this->sessionService->getFilter('country') ?: NULL,
            province: $this->sessionService->getFilter('province') ?: NULL,
            onlyType: (bool)$this->sessionService->getFilter('onlyType'),
            includeSynonym: (bool)$this->sessionService->getFilter('includeSynonym'),
            onlyImages: (bool)$this->sessionService->getFilter('onlyImages'),
            family: $this->sessionService->getFilter('family') ?: NULL,
            onlyCoords: (bool)$this->sessionService->getFilter('onlyCoords'),
            taxon: $this->sessionService->getFilter('taxon') ?: NULL,
            sort: $this->sessionService->getSort() ?: []
        );
    }
}
