<?php

declare(strict_types=1);

namespace JACQ\UI\Http;

use JACQ\Application\Specimen\Search\SpecimenSearchParameters;

final class SpecimenSearchParametersFromSessionFactory
{
    public function __construct(
        private SearchFormSessionService $sessionService
    ) {
    }

    public function create(): SpecimenSearchParameters
    {
        return new SpecimenSearchParameters(
            institution: (int) $this->sessionService->getFilter('institution') ?: null,
            herbNr: $this->sessionService->getFilter('herbNr') ?: null,
            collection: (int) $this->sessionService->getFilter('collection') ?: null,
            collectorNr: $this->sessionService->getFilter('collectorNr') ?: null,
            collector: $this->sessionService->getFilter('collector') ?: null,
            collectionDate: $this->sessionService->getFilter('collectionDate') ?: null,
            collectionNr: $this->sessionService->getFilter('collectionNr') ?: null,
            series: $this->sessionService->getFilter('series') ?: null,
            locality: $this->sessionService->getFilter('locality') ?: null,
            habitus: $this->sessionService->getFilter('habitus') ?: null,
            habitat: $this->sessionService->getFilter('habitat') ?: null,
            taxonAlternative: $this->sessionService->getFilter('taxonAlternative') ?: null,
            annotation: $this->sessionService->getFilter('annotation') ?: null,
            country: $this->sessionService->getFilter('country') ?: null,
            province: $this->sessionService->getFilter('province') ?: null,
            onlyType: (bool)$this->sessionService->getFilter('onlyType'),
            includeSynonym: (bool)$this->sessionService->getFilter('includeSynonym'),
            onlyImages: (bool)$this->sessionService->getFilter('onlyImages'),
            family: $this->sessionService->getFilter('family') ?: null,
            onlyCoords: (bool)$this->sessionService->getFilter('onlyCoords'),
            taxon: $this->sessionService->getFilter('taxon') ?: null,
            sort: $this->sessionService->getSort() ?: []
        );
    }
}
