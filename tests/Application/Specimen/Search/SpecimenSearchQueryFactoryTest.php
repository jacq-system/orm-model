<?php declare(strict_types=1);

namespace JACQ\Tests\Application\Specimen\Search;

use Doctrine\ORM\EntityManagerInterface;
use JACQ\Application\Specimen\Search\SpecimenSearchQuery;
use JACQ\Application\Specimen\Search\SpecimenSearchQueryFactory;
use JACQ\Application\Specimen\Search\Sort\SpecimenQuerySort;
use JACQ\Repository\Herbarinput\CollectorRepository;
use JACQ\Repository\Herbarinput\Collector2Repository;
use JACQ\Repository\Herbarinput\SpecimensRepository;
use JACQ\Service\SpeciesService;
use PHPUnit\Framework\TestCase;

class SpecimenSearchQueryFactoryTest extends TestCase
{
    private SpecimenSearchQueryFactory $factory;
    private EntityManagerInterface $entityManager;
    private SpeciesService $speciesService;
    private SpecimenQuerySort $specimenQuerySort;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getRepository')
            ->willReturnMap([
                [\JACQ\Entity\Jacq\Herbarinput\Collector::class, $this->createMock(CollectorRepository::class)],
                [\JACQ\Entity\Jacq\Herbarinput\Collector2::class, $this->createMock(Collector2Repository::class)],
                [\JACQ\Entity\Jacq\Herbarinput\Specimens::class, $this->createMock(SpecimensRepository::class)],
            ]);
        $this->entityManager->method('getConnection')
            ->willReturn($this->createMock(\Doctrine\DBAL\Connection::class));
        $this->speciesService = $this->createMock(SpeciesService::class);
        $this->specimenQuerySort = $this->createMock(SpecimenQuerySort::class);
        
        $this->factory = new SpecimenSearchQueryFactory(
            $this->entityManager,
            $this->speciesService,
            $this->specimenQuerySort
        );
    }

    public function testCreateForPublicReturnsSpecimenSearchQuery(): void
    {
        $query = $this->factory->createForPublic();
        
        $this->assertInstanceOf(SpecimenSearchQuery::class, $query);
    }

    public function testCreateForPublicWithCoordsReturnsSpecimenSearchQuery(): void
    {
        $query = $this->factory->createForPublicWithCoords();
        
        $this->assertInstanceOf(SpecimenSearchQuery::class, $query);
    }
}
