<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Export;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Entity\Jacq\Herbarinput\Specimens;
use JACQ\Service\SpeciesService;
use JACQ\Service\SpecimenService;

readonly class KmlService
{
    public const int EXPORT_LIMIT = 1500;

    public function __construct(protected SpecimenService $specimenService, protected SpeciesService $taxonService, protected EntityManagerInterface $entityManager)
    {
    }

    protected function KmlRecord(Specimens $specimen): string
    {
        $collectorText = $this->specimenService->getCollectionText($specimen);

        $location = $specimen->country?->nameEng;
        if (!empty($specimen->province?->name)) {
            $location .= " / " . trim($specimen->province->name);
        }
        if ($specimen->getLatitude() !== null && $specimen->getLongitude() !== null) {
            $location .= " / " . round($specimen->getLatitude(), 2) . "° / " . round($specimen->getLongitude(), 2) . "°";
        }

        if ($specimen->getLatitude() !== null && $specimen->getLongitude() !== null) {
            return "<Placemark>\n"
                . "  <name>" . htmlspecialchars($this->taxonService->taxonNameWithHybrids($specimen->species, true), ENT_NOQUOTES) . "</name>\n"
                . "  <description>\n"
                . "    <![CDATA[\n"
                . "      " . $this->addLine($specimen->herbCollection->name . " " . $specimen->herbNumber . " [dbID " . $specimen->id . "]")
                . "      " . $this->addLine($collectorText)
                . "      " . $this->addLine($specimen->getDate())
                . "      " . $this->addLine($location)
                . "      " . $this->addLine($specimen->locality)
                . "      " . $this->addLine($this->specimenService->getStableIdentifier($specimen))
                . "      <a href=\"" . $this->specimenService->getStableIdentifier($specimen) . "\">link</a>\n"
                . "    ]]>\n"
                . "  </description>\n"
                . "  <Point>\n"
                . "    <coordinates>" . $specimen->getLongitude() . ',' . $specimen->getLatitude() . "</coordinates>\n"
                . "  </Point>\n"
                . "</Placemark>\n";
        }
        return '';
    }

    protected function KmlRecordReduced(Specimens $specimen): string
    {
        if ($specimen->getLatitude() !== null && $specimen->getLongitude() !== null) {
            return "<Placemark>\n"
                . "  <name>" . $specimen->id . "</name>\n"
                . "  <Point>\n"
                . "    <coordinates>" . $specimen->getLongitude() . ',' . $specimen->getLatitude() . "</coordinates>\n"
                . "  </Point>\n"
                . "</Placemark>\n";
        }
        return '';
    }

    protected function addLine(?string $value): string
    {
        if (empty($value)) {
            return "";
        }
        return htmlspecialchars($value, ENT_NOQUOTES) . "<br>\n";
    }

    public function KmlRecords(QueryBuilder $queryBuilder, int $limit = self::EXPORT_LIMIT): \Generator
    {
        yield '<?xml version="1.0" encoding="UTF-8"?><kml xmlns="https://www.opengis.net/kml/2.2"><Document><description>search results Virtual Herbaria</description>';
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
                yield $this->KmlRecord($specimen);
                $rowsExported++;
                if ($rowsExported >= $limit) {
                    break 2; // zastavit vnitřní i vnější smyčku
                }

                $lastId = $specimen->id;
            }
            $this->entityManager->clear();
            yield '</Document></kml>';

        }

    }
}
