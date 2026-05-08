<?php

declare(strict_types=1);

namespace JACQ\Tests\Application\Specimen\Export;

use Doctrine\ORM\EntityManagerInterface;
use JACQ\Application\Specimen\Export\KmlService;
use JACQ\Service\SpeciesService;
use JACQ\Service\SpecimenService;
use PHPUnit\Framework\TestCase;

class KmlServiceTest extends TestCase
{
    private KmlService $service;
    private SpecimenService $specimenService;
    private SpeciesService $taxonService;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->specimenService = $this->createMock(SpecimenService::class);
        $this->taxonService = $this->createMock(SpeciesService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->service = new KmlService(
            $this->specimenService,
            $this->taxonService,
            new \JACQ\Application\Specimen\Search\SpecimenBatchProvider($this->entityManager),
            $this->entityManager
        );
    }

    public function testExportLimitConstant(): void
    {
        $this->assertEquals(1000, KmlService::EXPORT_LIMIT);
    }

    public function testKmlRecordWithCoordinates(): void
    {
        $species = $this->createMock(\JACQ\Entity\Jacq\Herbarinput\Species::class);
        $herbCollection = $this->createMock(\JACQ\Entity\Jacq\Herbarinput\HerbCollection::class);

        // Use reflection to set the protected properties on herbCollection
        $herbCollectionReflection = new \ReflectionClass($herbCollection);
        $collShortProperty = $herbCollectionReflection->getProperty('collShort');
        $collShortProperty->setAccessible(true);
        $collShortProperty->setValue($herbCollection, 'W');

        $nameProperty = $herbCollectionReflection->getProperty('name');
        $nameProperty->setAccessible(true);
        $nameProperty->setValue($herbCollection, 'Herbarium W');

        $specimen = $this->createMock(\JACQ\Entity\Jacq\Herbarinput\Specimens::class);
        $specimen->method('getLatitude')->willReturn(48.2082);
        $specimen->method('getLongitude')->willReturn(16.3738);
        $specimen->method('getDate')->willReturn('2024-01-15');

        // Use reflection to set the protected properties
        $specimenReflection = new \ReflectionClass($specimen);
        $idProperty = $specimenReflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($specimen, 1);

        $localityProperty = $specimenReflection->getProperty('locality');
        $localityProperty->setAccessible(true);
        $localityProperty->setValue($specimen, 'Vienna');

        $herbNumberProperty = $specimenReflection->getProperty('herbNumber');
        $herbNumberProperty->setAccessible(true);
        $herbNumberProperty->setValue($specimen, 'WU-123');

        $speciesProperty = $specimenReflection->getProperty('species');
        $speciesProperty->setAccessible(true);
        $speciesProperty->setValue($specimen, $species);

        $herbCollectionProperty = $specimenReflection->getProperty('herbCollection');
        $herbCollectionProperty->setAccessible(true);
        $herbCollectionProperty->setValue($specimen, $herbCollection);

        $this->specimenService->method('getCollectionText')
            ->willReturn('Collector 123');
        $this->specimenService->method('getStableIdentifier')
            ->willReturn('https://example.org/sid/1');
        $this->taxonService->method('taxonNameWithHybrids')
            ->willReturn('Test species');

        $serviceReflection = new \ReflectionClass($this->service);
        $method = $serviceReflection->getMethod('KmlRecord');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $specimen);

        $this->assertStringContainsString('<Placemark>', $result);
        $this->assertStringContainsString('<Point>', $result);
        $this->assertStringContainsString('<coordinates>16.3738,48.2082</coordinates>', $result);
    }

    public function testKmlRecordWithoutCoordinatesReturnsEmpty(): void
    {
        $specimen = $this->createMock(\JACQ\Entity\Jacq\Herbarinput\Specimens::class);
        $specimen->method('getLatitude')->willReturn(null);
        $specimen->method('getLongitude')->willReturn(null);

        $serviceReflection = new \ReflectionClass($this->service);
        $method = $serviceReflection->getMethod('KmlRecord');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $specimen);

        $this->assertEquals('', $result);
    }

    public function testKmlRecordReducedWithCoordinates(): void
    {
        $specimen = $this->createMock(\JACQ\Entity\Jacq\Herbarinput\Specimens::class);
        $specimen->method('getLatitude')->willReturn(48.2082);
        $specimen->method('getLongitude')->willReturn(16.3738);

        $specimenReflection = new \ReflectionClass($specimen);
        $idProperty = $specimenReflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($specimen, 999);

        $serviceReflection = new \ReflectionClass($this->service);
        $method = $serviceReflection->getMethod('KmlRecordReduced');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, $specimen);

        $this->assertStringContainsString('<Placemark>', $result);
        $this->assertStringContainsString('<name>999</name>', $result);
        $this->assertStringContainsString('<coordinates>16.3738,48.2082</coordinates>', $result);
    }

    public function testAddLineWithEmptyValue(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('addLine');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, '');

        $this->assertEquals('', $result);
    }

    public function testAddLineWithValue(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('addLine');
        $method->setAccessible(true);

        $result = $method->invoke($this->service, 'Test value');

        $this->assertEquals("Test value<br>\n", $result);
    }
}
