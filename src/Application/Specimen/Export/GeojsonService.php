<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Export;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenBatchProvider;
use JACQ\Entity\Jacq\Herbarinput\Specimens;

readonly class GeojsonService
{
    public const int EXPORT_LIMIT = 1000;

    public function __construct(protected SpecimenBatchProvider $specimenBatchProvider, protected EntityManagerInterface $entityManager)
    {
    }

    protected function GeoJsonRecord(Specimens $specimen): array
    {
        if ($specimen->getLatitude() !== null && $specimen->getLongitude() !== null) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$specimen->getLongitude(), $specimen->getLatitude()],
                ],
                'properties' => [
                    'id' => $specimen->id,
                ],
            ];
        }
        return [
            'type' => 'Feature',
            "geometry" => null,
            "properties" => [
                'id' => $specimen->id,
                "note" => "unknown location"
            ]
        ];
    }

    public function GeojsonRecords(QueryBuilder $queryBuilder, int $offset = 0, int $limit = self::EXPORT_LIMIT): \Generator
    {
        $first = true;

        yield '{"type":"FeatureCollection","features":[';
        foreach ($this->specimenBatchProvider->iterate($queryBuilder, $offset, $limit, 500) as $specimen) {
            if (!$first) {
                yield ',';
            }
            $first = false;
            yield json_encode($this->GeoJsonRecord($specimen));
        }
        yield ']}';
    }
}
