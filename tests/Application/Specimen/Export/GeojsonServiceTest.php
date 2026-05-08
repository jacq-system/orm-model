<?php declare(strict_types=1);

namespace JACQ\Tests\Application\Specimen\Export;

use Doctrine\ORM\EntityManagerInterface;
use JACQ\Application\Specimen\Export\GeojsonService;
use PHPUnit\Framework\TestCase;

class GeojsonServiceTest extends TestCase
{
    private GeojsonService $service;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        
        $this->service = new GeojsonService(
            new \JACQ\Application\Specimen\Search\SpecimenBatchProvider($this->entityManager),
            $this->entityManager
        );
    }

    public function testExportLimitConstant(): void
    {
        $this->assertEquals(1000, GeojsonService::EXPORT_LIMIT);
    }

    public function testGeoJsonRecordWithCoordinates(): void
    {
        $specimen = $this->createMock(\JACQ\Entity\Jacq\Herbarinput\Specimens::class);
        $specimen->method('getLatitude')->willReturn(48.2082);
        $specimen->method('getLongitude')->willReturn(16.3738);
        
        // Use reflection to set the protected id property
        $specimenReflection = new \ReflectionClass($specimen);
        $idProperty = $specimenReflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($specimen, 123);
        
        $serviceReflection = new \ReflectionClass($this->service);
        $method = $serviceReflection->getMethod('GeoJsonRecord');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->service, $specimen);
        
        $this->assertEquals('Feature', $result['type']);
        $this->assertEquals('Point', $result['geometry']['type']);
        $this->assertEquals([16.3738, 48.2082], $result['geometry']['coordinates']);
        $this->assertEquals(123, $result['properties']['id']);
    }

    public function testGeoJsonRecordWithoutCoordinates(): void
    {
        $specimen = $this->createMock(\JACQ\Entity\Jacq\Herbarinput\Specimens::class);
        $specimen->method('getLatitude')->willReturn(null);
        $specimen->method('getLongitude')->willReturn(null);
        
        $specimenReflection = new \ReflectionClass($specimen);
        $idProperty = $specimenReflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($specimen, 456);
        
        $serviceReflection = new \ReflectionClass($this->service);
        $method = $serviceReflection->getMethod('GeoJsonRecord');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->service, $specimen);
        
        $this->assertEquals('Feature', $result['type']);
        $this->assertNull($result['geometry']);
        $this->assertEquals(456, $result['properties']['id']);
        $this->assertEquals('unknown location', $result['properties']['note']);
    }
}