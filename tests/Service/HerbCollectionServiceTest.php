<?php declare(strict_types=1);

namespace JACQ\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use JACQ\Entity\Jacq\Herbarinput\HerbCollection;
use JACQ\Entity\Jacq\Herbarinput\Institution;
use JACQ\Entity\Jacq\HerbarPictures\IiifDefinition;
use JACQ\Service\HerbCollectionService;
use JACQ\Service\JacqNetworkService;
use PHPUnit\Framework\TestCase;

class HerbCollectionServiceTest extends TestCase
{
    private HerbCollectionService $service;
    private EntityManagerInterface $entityManager;
    private JacqNetworkService $jacqNetworkService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->jacqNetworkService = $this->createMock(JacqNetworkService::class);
        
        $this->service = new HerbCollectionService($this->entityManager, $this->jacqNetworkService);
    }

    private function createInstitutionWithId(int $id): Institution
    {
        $institution = new Institution();
        $reflection = new \ReflectionClass($institution);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($institution, $id);
        return $institution;
    }

    public function testGetIiifDefinitionReturnsIiifDefinition(): void
    {
        $institution = $this->createInstitutionWithId(1);
        
        $herbCollection = new HerbCollection();
        $reflection = new \ReflectionClass($herbCollection);
        $property = $reflection->getProperty('institution');
        $property->setAccessible(true);
        $property->setValue($herbCollection, $institution);
        
        $iiifDefinition = new IiifDefinition();
        
        $repository = $this->createMock(\Doctrine\ORM\EntityRepository::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(Query::class);
        
        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(IiifDefinition::class)
            ->willReturn($repository);
        
        $repository->expects($this->once())
            ->method('createQueryBuilder')
            ->with('imgdef')
            ->willReturn($queryBuilder);
        
        $queryBuilder->expects($this->once())
            ->method('where')
            ->with('imgdef.institution = :institution')
            ->willReturn($queryBuilder);
        
        $queryBuilder->expects($this->once())
            ->method('setParameter')
            ->with('institution', 1)
            ->willReturn($queryBuilder);
        
        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);
        
        $query->expects($this->once())
            ->method('getOneOrNullResult')
            ->willReturn($iiifDefinition);
        
        $result = $this->service->getIiifDefiniton($herbCollection);
        
        $this->assertSame($iiifDefinition, $result);
    }

    public function testGetIiifDefinitionReturnsNull(): void
    {
        $institution = $this->createInstitutionWithId(1);
        
        $herbCollection = new HerbCollection();
        $reflection = new \ReflectionClass($herbCollection);
        $property = $reflection->getProperty('institution');
        $property->setAccessible(true);
        $property->setValue($herbCollection, $institution);
        
        $repository = $this->createMock(\Doctrine\ORM\EntityRepository::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $query = $this->createMock(Query::class);
        
        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(IiifDefinition::class)
            ->willReturn($repository);
        
        $repository->expects($this->once())
            ->method('createQueryBuilder')
            ->with('imgdef')
            ->willReturn($queryBuilder);
        
        $queryBuilder->expects($this->once())
            ->method('where')
            ->with('imgdef.institution = :institution')
            ->willReturn($queryBuilder);
        
        $queryBuilder->expects($this->once())
            ->method('setParameter')
            ->with('institution', 1)
            ->willReturn($queryBuilder);
        
        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);
        
        $query->expects($this->once())
            ->method('getOneOrNullResult')
            ->willReturn(null);
        
        $result = $this->service->getIiifDefiniton($herbCollection);
        
        $this->assertNull($result);
    }
}