<?php declare(strict_types=1);

namespace JACQ\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use JACQ\Service\JacqNetworkService;
use JACQ\Service\ReferenceService;
use PHPUnit\Framework\TestCase;

class ReferenceServiceTest extends TestCase
{
    private ReferenceService $service;
    private EntityManagerInterface $entityManager;
    private JacqNetworkService $jacqNetworkService;
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getConnection')->willReturn($this->connection);
        $this->jacqNetworkService = $this->createMock(JacqNetworkService::class);
        
        $this->service = new ReferenceService($this->entityManager, $this->jacqNetworkService);
    }

    public function testGetCitationReferencesWithId(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([['name' => 'Test', 'id' => 1]]);
        
        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);
        
        $references = $this->service->getCitationReferences(1);
        $this->assertCount(1, $references);
        $this->assertEquals('Test', $references[0]['name']);
    }

    public function testGetCitationReferencesWithoutId(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([['name' => 'Test', 'id' => 1]]);
        
        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);
        
        $references = $this->service->getCitationReferences(null);
        $this->assertCount(1, $references);
    }

    public function testGetCitationReferencesWithEmptyId(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([]);
        
        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);
        
        $references = $this->service->getCitationReferences(0);
        $this->assertEmpty($references);
    }

    public function testGetPeriodicalReferencesWithId(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([['name' => 'Journal', 'id' => 1]]);
        
        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);
        
        $references = $this->service->getPeriodicalReferences(1);
        $this->assertCount(1, $references);
    }

    public function testGetPeriodicalReferencesWithoutId(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([]);
        
        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);
        
        $references = $this->service->getPeriodicalReferences(null);
        $this->assertIsArray($references);
    }

    public function testGetCitationChildrenReferences(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([
            ['scientificName' => 'Test species', 'taxonID' => 1, 'hasChildren' => 0, 'hasSynonyms' => 1]
        ]);
        
        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);
        
        $references = $this->service->getCitationChildrenReferences(1, 0);
        $this->assertCount(1, $references);
    }

    public function testGetCitationChildrenReferencesWithTaxonId(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([]);
        
        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);
        
        $references = $this->service->getCitationChildrenReferences(1, 5);
        $this->assertIsArray($references);
    }

    public function testFindCitationsId(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchFirstColumn')->willReturn([1, 2, 3]);
        
        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);
        
        $ids = $this->service->findCitationsId(1, 2, 3);
        $this->assertEquals([1, 2, 3], $ids);
    }
}