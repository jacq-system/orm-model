<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Export;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenBatchProvider;
use JACQ\Entity\Jacq\Herbarinput\Specimens;
use JACQ\Service\GeoService;
use JACQ\Service\SpecimenService;
use JACQ\Service\TypusService;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelService
{
    public function __construct(protected GeoService $geoService, protected SpecimenService $specimenService, protected TypusService $typusService, protected SpecimenBatchProvider $specimenBatchProvider, protected EntityManagerInterface $entityManager)
    {
    }

    public const int EXPORT_LIMIT = 1000;

    public const array HEADER = ['Specimen ID', 'observation', 'dig_image', 'dig_img_obs', 'Institution_Code', 'Herbarium-Number/BarCode', 'institution_subcollection', 'Collection Number', 'Type information', 'Typified by', 'Taxon', 'status', 'Genus', 'Species', 'Author', 'Rank', 'Infra_spec', 'Infra_author', 'Family', 'Garden', 'voucher', 'Collection', 'First_collector', 'First_collectors_number', 'Add_collectors', 'Alt_number', 'Series', 'Series_number', 'Coll_Date', 'Coll_Date_2', 'Country', 'Province', 'geonames', 'Latitude', 'Latitude_DMS', 'Lat_Hemisphere', 'Lat_degree', 'Lat_minute', 'Lat_second', 'Longitude', 'Longitude_DMS', 'Long_Hemisphere', 'Long_degree', 'Long_minute', 'Long_second', 'exactness', 'Altitude lower', 'Altitude higher', 'Quadrant', 'Quadrant_sub', 'Location', 'det./rev./conf./assigned', 'ident. history', 'annotations', 'habitat', 'habitus', 'stable identifier'];

    protected function prepareExcel($title = "specimens_download"): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->setTitle('JACQ specimens export');

        $spreadsheet->getProperties()->setCreator('JACQ')->setLastModifiedBy('JACQ contributors')->setTitle($title)->setSubject('export date: ' . date('d.j.Y', time()))->setDescription("")->setKeywords("JACQ, export");

        $spreadsheet->getActiveSheet()->getStyle('A1:BE1')->getFont()->setBold(true);
        return $spreadsheet;
    }

    /**
     * Fills first line with header and from A2 the body
     */
    protected function easyFillExcel(Spreadsheet $spreadsheet, array $header, array $body): Spreadsheet
    {
        try {
            $spreadsheet->getActiveSheet()->fromArray($header);
            $spreadsheet->getActiveSheet()->fromArray($body, NULL, 'A2');
        } catch (Exception $exception) {
        }
        return $spreadsheet;
    }

    protected function prepareRowForExport(mixed $specimen): array
    {
        if (is_int($specimen)) {
            $specimen = $this->entityManager->getRepository(Specimens::class)->find($specimen);
        }

        $infraInfo = $specimen->species->getInfraEpithet();

        $specimen->getLatitude() ? $latDMS = $this->geoService->decimalToDMS($specimen->getLatitude()) . ' ' . $specimen->getHemisphereLatitude() : $latDMS = null;
        $specimen->getLongitude() ? $lonDMS = $this->geoService->decimalToDMS($specimen->getLongitude()) . ' ' . $specimen->getHemisphereLongitude() : $lonDMS = null;

        return [
            $specimen->id,
            $specimen->observation ? 1 : '',
            $specimen->image ? 1 : '',
            $specimen->imageObservation ? 1 : '',
            $specimen->herbCollection->institution->code,
            $specimen->herbNumber,
            $specimen->herbCollection->collShort,
            $specimen->collectionNumber,
            $this->typusService->getTypusText($specimen),
            $specimen->typified,
            $specimen->species->materializedName->scientificName,
            $specimen->identificationStatus?->name,
            $specimen->species->genus->name,
            $specimen->species->epithetSpecies?->name,
            $specimen->species->authorSpecies?->name,
            $specimen->species->rank->abbreviation,
            $infraInfo['epithet'],
            $infraInfo['author'],
            $specimen->species->genus->family->name,
            $specimen->garden,
            $specimen->voucher?->name,
            $this->specimenService->getCollectionText($specimen),
            $specimen->collector?->name,
            $specimen->number,
            $specimen->collector2?->name,
            $specimen->altNumber,
            $specimen->series?->name,
            $specimen->seriesNumber,
            $specimen->getDate(),
            $specimen->getDate2(),
            $specimen->country?->nameEng,
            $specimen->province?->name,
            $specimen->region,
            $specimen->getLatitude() ? number_format(round($specimen->getLatitude(), 9), 9) . '°' : '',
            $latDMS,
            $specimen->getHemisphereLatitude(),
            ($specimen->getHemisphereLatitude() === 'N') ? $specimen->degreeN : $specimen->degreeS,
            ($specimen->getHemisphereLatitude() === 'N') ? $specimen->minuteN : $specimen->minuteS,
            ($specimen->getHemisphereLatitude() === 'N') ? $specimen->secondN : $specimen->secondS,
            $specimen->getLongitude() ? number_format(round($specimen->getLongitude(), 9), 9) . '°' : '',
            $lonDMS,
            $specimen->getHemisphereLongitude(),
            ($specimen->getHemisphereLongitude() === 'E') ? $specimen->degreeE : $specimen->degreeW,
            ($specimen->getHemisphereLongitude() === 'E') ? $specimen->minuteE : $specimen->minuteW,
            ($specimen->getHemisphereLongitude() === 'E') ? $specimen->secondE : $specimen->secondW,
            $specimen->exactness,
            $specimen->altitudeMin,
            $specimen->altitudeMax,
            $specimen->quadrant,
            $specimen->quadrantSub,
            $specimen->locality,
            $specimen->determination,
            $specimen->taxonAlternative,
            /**
             * formerly was the "=" character removed, now only prepend apostrophe -> force cell as a string to prevent a starting "=" be interpreted as a formula
             */
            ((str_starts_with((string)$specimen->getAnnotation(), '=')) ? "'" : "") . $specimen->getAnnotation(),
            ((str_starts_with((string)$specimen->habitat, '=')) ? "'" : "") . $specimen->habitat,
            ((str_starts_with((string)$specimen->habitus, '=')) ? "'" : "") . $specimen->habitus,
            $this->specimenService->getStableIdentifier($specimen)
        ];

    }

    public function createSpecimenExport(QueryBuilder $queryBuilder, int $limit = self::EXPORT_LIMIT): Spreadsheet
    {
        $spreadsheet = $this->prepareExcel();
        $spreadsheet = $this->easyFillExcel($spreadsheet, ExcelService::HEADER, []);

        foreach ($this->specimenBatchProvider->iterate($queryBuilder, 0, $limit) as $specimen) {
                $rowData = $this->prepareRowForExport($specimen);
                $spreadsheet->getActiveSheet()->fromArray($rowData, null, 'A' . ($spreadsheet->getActiveSheet()->getHighestRow() + 1));
            }

        return $spreadsheet;
    }
}
