<?php

declare(strict_types=1);

namespace JACQ\Tests\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use JACQ\Service\SpeciesService;
use JACQ\Service\TypusService;
use PHPUnit\Framework\TestCase;

class TypusServiceTest extends TestCase
{
    private TypusService $service;
    private EntityManagerInterface $entityManager;
    private SpeciesService $speciesService;
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getConnection')->willReturn($this->connection);
        $this->speciesService = $this->createMock(SpeciesService::class);

        $this->service = new TypusService($this->entityManager, $this->speciesService);
    }

    public function testServiceInstantiates(): void
    {
        // This test verifies that the service can be instantiated
        // Other tests require mocking Doctrine entities which use magic methods
        $this->assertInstanceOf(TypusService::class, $this->service);
    }
}
