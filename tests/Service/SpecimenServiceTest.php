<?php declare(strict_types=1);

namespace JACQ\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use JACQ\Entity\Jacq\GbifPilot\EuropeanaImages;
use JACQ\Entity\Jacq\Herbarinput\Specimens;
use JACQ\Repository\Herbarinput\SpecimensRepository;
use JACQ\Service\JacqNetworkService;
use JACQ\Service\SpecimenService;
use JACQ\Service\TypusService;
use PHPUnit\Framework\TestCase;

class SpecimenServiceTest extends TestCase
{
    private SpecimenService $service;
    private EntityManagerInterface $entityManager;
    private SpecimensRepository $specimensRepository;
    private JacqNetworkService $jacqNetworkService;
    private TypusService $typusService;
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getConnection')->willReturn($this->connection);
        $this->specimensRepository = $this->createMock(SpecimensRepository::class);
        $this->jacqNetworkService = $this->createMock(JacqNetworkService::class);
        $this->typusService = $this->createMock(TypusService::class);
        
        $this->service = new SpecimenService(
            $this->entityManager,
            $this->specimensRepository,
            $this->jacqNetworkService,
            $this->typusService
        );
    }

    public function testFindSpecimenUsingSidWithJacqId(): void
    {
        $specimen = $this->createMock(Specimens::class);
        
        $this->specimensRepository->expects($this->once())
            ->method('findAccessibleForPublic')
            ->with(123)
            ->willReturn($specimen);
        
        $result = $this->service->findSpecimenUsingSid('JACQID123');
        $this->assertSame($specimen, $result);
    }

    public function testFindAccessibleForPublicThrowsExceptionWhenNotFound(): void
    {
        $this->specimensRepository->method('findAccessibleForPublic')->willReturn(null);
        
        $this->expectException(\Doctrine\ORM\EntityNotFoundException::class);
        $this->service->findAccessibleForPublic(1);
    }

    public function testFindAccessibleForPublicReturnsSpecimen(): void
    {
        $specimen = $this->createMock(Specimens::class);
        $this->specimensRepository->method('findAccessibleForPublic')->willReturn($specimen);
        
        $result = $this->service->findAccessibleForPublic(1);
        $this->assertSame($specimen, $result);
    }

    public function testFindNonAccessibleForPublicThrowsExceptionWhenNotFound(): void
    {
        $this->specimensRepository->method('findNonAccessibleForPublic')->willReturn(null);
        
        $this->expectException(\Doctrine\ORM\EntityNotFoundException::class);
        $this->service->findNonAccessibleForPublic(1);
    }

    public function testGetEuropeanaImagesReturnsNull(): void
    {
        $specimen = $this->createMock(Specimens::class);
        
        $queryBuilder = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $query = $this->createMock(\Doctrine\ORM\Query::class);
        
        $this->entityManager->method('createQueryBuilder')->willReturn($queryBuilder);
        $queryBuilder->method('select')->willReturn($queryBuilder);
        $queryBuilder->method('from')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('getQuery')->willReturn($query);
        $query->method('getOneOrNullResult')->willReturn(null);
        
        $result = $this->service->getEuropeanaImages($specimen);
        $this->assertNull($result);
    }

    public function testGetEuropeanaImagesReturnsEuropeanaImages(): void
    {
        $specimen = $this->createMock(Specimens::class);
        $europeanaImages = new EuropeanaImages();
        
        $queryBuilder = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $query = $this->createMock(\Doctrine\ORM\Query::class);
        
        $this->entityManager->method('createQueryBuilder')->willReturn($queryBuilder);
        $queryBuilder->method('select')->willReturn($queryBuilder);
        $queryBuilder->method('from')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('getQuery')->willReturn($query);
        $query->method('getOneOrNullResult')->willReturn($europeanaImages);
        
        $result = $this->service->getEuropeanaImages($specimen);
        $this->assertSame($europeanaImages, $result);
    }

    public function testGetPhaidraImagesReturnsNull(): void
    {
        $specimen = $this->createMock(Specimens::class);
        
        $queryBuilder = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $query = $this->createMock(\Doctrine\ORM\Query::class);
        
        $this->entityManager->method('createQueryBuilder')->willReturn($queryBuilder);
        $queryBuilder->method('select')->willReturn($queryBuilder);
        $queryBuilder->method('from')->willReturn($queryBuilder);
        $queryBuilder->method('where')->willReturn($queryBuilder);
        $queryBuilder->method('setParameter')->willReturn($queryBuilder);
        $queryBuilder->method('getQuery')->willReturn($query);
        $query->method('getOneOrNullResult')->willReturn(null);
        
        $result = $this->service->getPhaidraImages($specimen);
        $this->assertNull($result);
    }
}