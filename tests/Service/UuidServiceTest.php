<?php

declare(strict_types=1);

namespace JACQ\Tests\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Doctrine\ORM\EntityManagerInterface;
use JACQ\Service\UuidConfiguration;
use JACQ\Service\UuidService;
use PHPUnit\Framework\TestCase;

class UuidServiceTest extends TestCase
{
    private UuidService $service;
    private EntityManagerInterface $entityManager;
    private UuidConfiguration $uuidConfiguration;
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getConnection')->willReturn($this->connection);

        $this->uuidConfiguration = new UuidConfiguration('https://example.com/api/', 'test-secret', 'https://resolve.jacq.org/');

        $this->service = new UuidService($this->entityManager, $this->uuidConfiguration);
    }

    public function testGetUuidReturnsUuidFromDatabase(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchOne')->willReturn('abc-123-uuid');

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $uuid = $this->service->getUuid('taxon', 1);
        $this->assertEquals('abc-123-uuid', $uuid);
    }

    public function testGetUuidReturnsEmptyWhenNotFound(): void
    {
        $result = $this->createMock(Result::class);
        $result->method('fetchOne')->willReturn(false);

        $this->connection->expects($this->once())
            ->method('executeQuery')
            ->willReturn($result);

        $uuid = $this->service->getUuid('taxon', 1);
        $this->assertIsString($uuid);
    }

    public function testGetResolvableUri(): void
    {
        $uri = $this->service->getResolvableUri('abc-123');
        $this->assertEquals('https://resolve.jacq.org/abc-123', $uri);
    }
}
