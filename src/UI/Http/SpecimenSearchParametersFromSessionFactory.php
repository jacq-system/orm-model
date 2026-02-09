<?php declare(strict_types=1);

namespace JACQ\UI\Http;

use JACQ\Application\SearchSpecimen\SpecimenSearchParameters;

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
            institution: $this->sessionService->getFilter('institution'),
            herbNr: $this->sessionService->getFilter('herbNr'),
            collection: $this->sessionService->getFilter('collection'),
            collectorNr: $this->sessionService->getFilter('collectorNr'),
            collector: $this->sessionService->getFilter('collector'),
            collectionDate: $this->sessionService->getFilter('collectionDate'),
            collectionNr: $this->sessionService->getFilter('collectionNr'),
            series: $this->sessionService->getFilter('series'),
            locality: $this->sessionService->getFilter('locality'),
            habitus: $this->sessionService->getFilter('habitus'),
            habitat: $this->sessionService->getFilter('habitat'),
            taxonAlternative: $this->sessionService->getFilter('taxonAlternative'),
            annotation: $this->sessionService->getFilter('annotation'),
            country: $this->sessionService->getFilter('country'),
            province: $this->sessionService->getFilter('province'),
            onlyType: (bool)$this->sessionService->getFilter('onlyType'),
            includeSynonym: (bool)$this->sessionService->getFilter('includeSynonym'),
            onlyImages: (bool)$this->sessionService->getFilter('onlyImages'),
            family: $this->sessionService->getFilter('family'),
            onlyCoords: (bool)$this->sessionService->getFilter('onlyCoords'),
            taxon: $this->sessionService->getFilter('taxon'),
        );
    }
}
