<?php declare(strict_types=1);

namespace JACQ\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use JACQ\Service\GeoService;
use JACQ\Service\JacqNetworkService;
use PHPUnit\Framework\TestCase;

class GeoServiceTest extends TestCase
{
    private GeoService $service;

    protected function setUp(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $jacqNetworkService = $this->createMock(JacqNetworkService::class);
        
        $this->service = new GeoService($entityManager, $jacqNetworkService);
    }

    public function testDecimalToDMSWithPositiveValue(): void
    {
        $result = $this->service->decimalToDMS(48.2082);
        $this->assertMatchesRegularExpression('/^\d+° \d+\'\d+(\.\d+)?"$/', $result);
    }

    public function testDecimalToDMSWithNegativeValue(): void
    {
        $result = $this->service->decimalToDMS(-16.5);
        $this->assertMatchesRegularExpression('/^-?\d+° \d+\'\d+(\.\d+)?"$/', $result);
    }

    public function testDecimalToDMSWithZero(): void
    {
        $result = $this->service->decimalToDMS(0);
        $this->assertEquals("0° 0'0\"", $result);
    }

    public function testDecimalToDMSWithExactDegrees(): void
    {
        $result = $this->service->decimalToDMS(45);
        $this->assertEquals("45° 0'0\"", $result);
    }

    public function testDecimalToDMSWithHalfDegree(): void
    {
        $result = $this->service->decimalToDMS(45.5);
        $this->assertEquals("45° 30'0\"", $result);
    }

    public function testDecimalToDMSWithQuarterDegree(): void
    {
        $result = $this->service->decimalToDMS(45.25);
        $this->assertEquals("45° 15'0\"", $result);
    }
}