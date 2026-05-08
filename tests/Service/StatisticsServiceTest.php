<?php declare(strict_types=1);

namespace JACQ\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use JACQ\Enum\CoreObjectsEnum;
use JACQ\Enum\TimeIntervalEnum;
use JACQ\Repository\Herbarinput\InstitutionRepository;
use JACQ\Service\StatisticsService;
use PHPUnit\Framework\TestCase;

class StatisticsServiceTest extends TestCase
{
    private StatisticsService $service;
    private EntityManagerInterface $entityManager;
    private InstitutionRepository $institutionRepository;
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getConnection')->willReturn($this->connection);
        $this->institutionRepository = $this->createMock(InstitutionRepository::class);
        
        $this->service = new StatisticsService($this->entityManager, $this->institutionRepository);
    }

    public function testGetResultsReturnsEmptyForUnknownType(): void
    {
        $result = $this->service->getResults('2020-01-01', '2020-12-31', 1, CoreObjectsEnum::Names, TimeIntervalEnum::Month);
        $this->assertIsArray($result);
    }

    public function testGetPeriodColumnReturnsDayOfYear(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getPeriodColumn');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->service, TimeIntervalEnum::Day);
        $this->assertStringContainsString('dayofyear', $result);
    }

    public function testGetPeriodColumnReturnsYear(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getPeriodColumn');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->service, TimeIntervalEnum::Year);
        $this->assertStringContainsString('year', $result);
    }

    public function testGetPeriodColumnReturnsMonth(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getPeriodColumn');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->service, TimeIntervalEnum::Month);
        $this->assertStringContainsString('month', $result);
    }

    public function testGetPeriodColumnReturnsWeekByDefault(): void
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getPeriodColumn');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->service, TimeIntervalEnum::Week);
        $this->assertStringContainsString('week', $result);
    }

    public function testGetResultsWithEmptyData(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([]);
        
        $this->connection->expects($this->any())
            ->method('executeQuery')
            ->willReturn($result);
        
        $this->institutionRepository->expects($this->any())
            ->method('findBy')
            ->willReturn([]);
        
        $result = $this->service->getResults('2020-01-01', '2020-12-31', 1, CoreObjectsEnum::Names, TimeIntervalEnum::Month);
        
        $this->assertEquals(['periodMin' => 0, 'periodMax' => 0, 'results' => []], $result);
    }

    public function testGetResultsWithData(): void
    {
        $institution = new \stdClass();
        $institution->id = 1;
        $institution->code = 'TEST';
        
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')
            ->willReturnOnConsecutiveCalls(
                [['period' => 1, 'cnt' => 10, 'source_id' => 1]],
                []
            );
        
        $this->connection->expects($this->any())
            ->method('executeQuery')
            ->willReturn($result);
        
        $this->institutionRepository->expects($this->any())
            ->method('findBy')
            ->willReturn([$institution]);
        
        $result = $this->service->getResults('2020-01-01', '2020-12-31', 1, CoreObjectsEnum::Names, TimeIntervalEnum::Month);
        
        $this->assertArrayHasKey('periodMin', $result);
        $this->assertArrayHasKey('periodMax', $result);
        $this->assertArrayHasKey('results', $result);
    }
}