<?php

declare(strict_types=1);

namespace JACQ\Tests\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManagerInterface;
use JACQ\Service\SpeciesService;
use PHPUnit\Framework\TestCase;

class SpeciesServiceTest extends TestCase
{
    private SpeciesService $service;
    private EntityManagerInterface $entityManager;
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getConnection')->willReturn($this->connection);

        $this->service = new SpeciesService($this->entityManager);
    }

    public function testFulltextSearchReturnsEmptyArrayForEmptyTerm(): void
    {
        $result = $this->service->fulltextSearch('');
        $this->assertEmpty($result);
    }

    public function testFulltextSearchReturnsIdsOnly(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchFirstColumn')->willReturn([1, 2, 3]);

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $result = $this->service->fulltextSearch('test', true);
        $this->assertEquals([1, 2, 3], $result);
    }

    public function testFulltextSearchReturnsAssociativeArray(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([
            ['taxonID' => 1, 'scientificName' => 'Test species', 'taxonName' => 'Test']
        ]);

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $result = $this->service->fulltextSearch('test', false);
        $this->assertEquals([
            ['taxonID' => 1, 'scientificName' => 'Test species', 'taxonName' => 'Test']
        ], $result);
    }

    public function testIsAcceptedTaxonPartOfClassificationReturnsTrue(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchOne')->willReturn(1);

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $this->assertTrue($this->service->isAcceptedTaxonPartOfClassification(1, 2));
    }

    public function testIsAcceptedTaxonPartOfClassificationReturnsFalse(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchOne')->willReturn(0);

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $this->assertFalse($this->service->isAcceptedTaxonPartOfClassification(1, 2));
    }

    public function testHasTypeReturnsTrue(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturn(['specimen_ID' => 1]);

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $this->assertTrue($this->service->hasType(1));
    }

    public function testHasTypeReturnsFalse(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAssociative')->willReturn(false);

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $this->assertFalse($this->service->hasType(1));
    }

    public function testFindSynonymsReturnsArray(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchAllAssociative')->willReturn([
            ['scientificName' => 'Test species', 'taxonID' => 1, 'homotype' => 1]
        ]);

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $result = $this->service->findSynonyms(1, 2);
        $this->assertEquals([
            ['scientificName' => 'Test species', 'taxonID' => 1, 'homotype' => 1]
        ], $result);
    }
}
