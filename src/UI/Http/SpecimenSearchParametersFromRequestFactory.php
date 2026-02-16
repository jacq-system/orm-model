<?php declare(strict_types=1);

namespace JACQ\UI\Http;


use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use Symfony\Component\HttpFoundation\Request;

final class SpecimenSearchParametersFromRequestFactory
{
    public function create(Request $request): SpecimenSearchParameters
    {
        return new SpecimenSearchParameters(
            institution: $request->query->getInt('institution') ?: null,
            herbNr: $request->query->get('herbNr'),
            collection: $request->query->getInt('collection') ?: null,
            collectorNr: $request->query->get('collectorNr'),
            collector: $request->query->get('collector'),
            collectionDate: $request->query->get('collectionDate'),
            collectionNr: $request->query->get('collectionNr'),
            series: $request->query->get('series'),
            locality: $request->query->get('locality'),
            habitus: $request->query->get('habitus'),
            habitat: $request->query->get('habitat'),
            taxonAlternative: $request->query->get('taxonAlternative'),
            annotation: $request->query->get('annotation'),
            country: $request->query->get('country'),
            province: $request->query->get('province'),
            onlyType: $request->query->getBoolean('onlyType'),
            includeSynonym: $request->query->getBoolean('includeSynonym'),
            onlyImages: $request->query->getBoolean('onlyImages'),
            family: $request->query->get('family'),
            onlyCoords: $request->query->getBoolean('onlyCoords'),
            taxon: $request->query->get('taxon'),
        );
    }

    public function createFromLegacy(Request $request): SpecimenSearchParameters
    {
        return new SpecimenSearchParameters(
            institutionCode: $request->query->get('sc') ?: null,
            herbNr: $request->query->get('herbnr'),
            collector: $request->query->get('coll'),
            country: $request->query->get('nation'),
            onlyType: $request->query->getBoolean('type'),
            onlyImages: $request->query->getBoolean('withImages'),
            taxon: $request->query->get('term'),
        );
    }
}
