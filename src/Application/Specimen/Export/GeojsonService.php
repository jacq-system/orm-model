<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Export;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Entity\Jacq\Herbarinput\Specimens;

readonly class GeojsonService
{
    public const int EXPORT_LIMIT = 1500;

    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    protected function GeoJsonRecord(Specimens $specimen): array
    {
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

    public function GeojsonRecords(QueryBuilder $queryBuilder, int $limit = self::EXPORT_LIMIT): \Generator
    {
        yield '{"type":"FeatureCollection","features":[';
        $batchSize = 300;
        $lastId = 0;
        $rowsExported = 0;

        while ($rowsExported < $limit) {
            $qb = clone $queryBuilder;

            $iterableResult = $qb
                ->andWhere('specimen.id > :lastId')
                ->setParameter('lastId', $lastId)
                ->setMaxResults($batchSize)
                ->resetDQLPart('select')
                ->select('specimen')
                ->getQuery()
                ->toIterable();

            if (!$iterableResult) {
                break;
            }

            foreach ($iterableResult as $specimen) {
                yield $this->GeoJsonRecord($specimen);
                $rowsExported++;
                if ($rowsExported >= $limit) {
                    break 2; // zastavit vnitřní i vnější smyčku
                }

                $lastId = $specimen->id;
            }
            $this->entityManager->clear();
            yield ']}';

        }

    }
}
