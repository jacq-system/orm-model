<?php declare(strict_types=1);

namespace JACQ\Tests\Application\Specimen\Export;

use Doctrine\ORM\EntityManagerInterface;
use JACQ\Application\Specimen\Export\ExcelService;
use PHPUnit\Framework\TestCase;

class ExcelServiceTest extends TestCase
{
    private ExcelService $service;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        
        $this->service = new ExcelService(
            $this->createMock(\JACQ\Service\GeoService::class),
            $this->createMock(\JACQ\Service\SpecimenService::class),
            $this->createMock(\JACQ\Service\TypusService::class),
            new \JACQ\Application\Specimen\Search\SpecimenBatchProvider($this->entityManager),
            $this->entityManager
        );
    }

    public function testHeaderConstantIsArray(): void
    {
        $this->assertIsArray(ExcelService::HEADER);
        $this->assertCount(57, ExcelService::HEADER);
    }

    public function testExportLimitConstant(): void
    {
        $this->assertEquals(1000, ExcelService::EXPORT_LIMIT);
    }
}