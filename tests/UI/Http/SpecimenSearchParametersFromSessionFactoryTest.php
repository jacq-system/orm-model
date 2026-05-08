<?php

declare(strict_types=1);

namespace JACQ\Tests\UI\Http;

use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use JACQ\UI\Http\SearchFormSessionService;
use JACQ\UI\Http\SpecimenSearchParametersFromSessionFactory;
use PHPUnit\Framework\TestCase;

class SpecimenSearchParametersFromSessionFactoryTest extends TestCase
{
    private SearchFormSessionService $sessionService;
    private SpecimenSearchParametersFromSessionFactory $factory;

    protected function setUp(): void
    {
        $this->sessionService = $this->createMock(SearchFormSessionService::class);
        $this->factory = new SpecimenSearchParametersFromSessionFactory($this->sessionService);
    }

    public function testCreateWithEmptySession(): void
    {
        $this->sessionService->method('getFilter')->willReturn(null);
        $this->sessionService->method('getSort')->willReturn(null);

        $result = $this->factory->create();

        $this->assertInstanceOf(SpecimenSearchParameters::class, $result);
        $this->assertNull($result->institution);
        $this->assertNull($result->herbNr);
        $this->assertNull($result->collection);
        $this->assertNull($result->collectorNr);
        $this->assertNull($result->collector);
        $this->assertNull($result->collectionDate);
        $this->assertNull($result->collectionNr);
        $this->assertNull($result->series);
        $this->assertNull($result->locality);
        $this->assertNull($result->habitus);
        $this->assertNull($result->habitat);
        $this->assertNull($result->taxonAlternative);
        $this->assertNull($result->annotation);
        $this->assertNull($result->country);
        $this->assertNull($result->province);
        $this->assertFalse($result->onlyType);
        $this->assertFalse($result->includeSynonym);
        $this->assertFalse($result->onlyImages);
        $this->assertNull($result->family);
        $this->assertFalse($result->onlyCoords);
        $this->assertNull($result->taxon);
        $this->assertSame([], $result->sort);
    }

    public function testCreateWithAllFiltersFromSession(): void
    {
        $this->sessionService->method('getFilter')
            ->willReturnMap([
                ['institution', 1],
                ['herbNr', 'HERB-001'],
                ['collection', 2],
                ['collectorNr', 'CN-001'],
                ['collector', 'Smith'],
                ['collectionDate', '2024-01-01'],
                ['collectionNr', 'COLL-001'],
                ['series', 'Series A'],
                ['locality', 'Vienna'],
                ['habitus', 'Tree'],
                ['habitat', 'Forest'],
                ['taxonAlternative', 'Alternative Taxon'],
                ['annotation', 'Annotated'],
                ['country', 'Austria'],
                ['province', 'Lower Austria'],
                ['onlyType', true],
                ['includeSynonym', true],
                ['onlyImages', true],
                ['family', 'Rosaceae'],
                ['onlyCoords', true],
                ['taxon', 'Rosa'],
            ]);

        $this->sessionService->method('getSort')->willReturn(['name' => 'ASC']);

        $result = $this->factory->create();

        $this->assertSame(1, $result->institution);
        $this->assertSame('HERB-001', $result->herbNr);
        $this->assertSame(2, $result->collection);
        $this->assertSame('CN-001', $result->collectorNr);
        $this->assertSame('Smith', $result->collector);
        $this->assertSame('2024-01-01', $result->collectionDate);
        $this->assertSame('COLL-001', $result->collectionNr);
        $this->assertSame('Series A', $result->series);
        $this->assertSame('Vienna', $result->locality);
        $this->assertSame('Tree', $result->habitus);
        $this->assertSame('Forest', $result->habitat);
        $this->assertSame('Alternative Taxon', $result->taxonAlternative);
        $this->assertSame('Annotated', $result->annotation);
        $this->assertSame('Austria', $result->country);
        $this->assertSame('Lower Austria', $result->province);
        $this->assertTrue($result->onlyType);
        $this->assertTrue($result->includeSynonym);
        $this->assertTrue($result->onlyImages);
        $this->assertSame('Rosaceae', $result->family);
        $this->assertTrue($result->onlyCoords);
        $this->assertSame('Rosa', $result->taxon);
        $this->assertSame(['name' => 'ASC'], $result->sort);
    }

    public function testCreateWithInstitutionZero(): void
    {
        $this->sessionService->method('getFilter')
            ->willReturnMap([
                ['institution', 0],
            ]);
        $this->sessionService->method('getSort')->willReturn(null);

        $result = $this->factory->create();

        $this->assertNull($result->institution);
    }

    public function testCreateWithCollectionZero(): void
    {
        $this->sessionService->method('getFilter')
            ->willReturnMap([
                ['collection', 0],
            ]);
        $this->sessionService->method('getSort')->willReturn(null);

        $result = $this->factory->create();

        $this->assertNull($result->collection);
    }

    public function testCreateWithFalseBooleanValues(): void
    {
        $this->sessionService->method('getFilter')
            ->willReturnMap([
                ['onlyType', false],
                ['onlyImages', false],
                ['onlyCoords', false],
                ['includeSynonym', false],
            ]);
        $this->sessionService->method('getSort')->willReturn(null);

        $result = $this->factory->create();

        $this->assertFalse($result->onlyType);
        $this->assertFalse($result->onlyImages);
        $this->assertFalse($result->onlyCoords);
        $this->assertFalse($result->includeSynonym);
    }

    public function testCreateWithEmptySortReturnsEmptyArray(): void
    {
        $this->sessionService->method('getFilter')->willReturn(null);
        $this->sessionService->method('getSort')->willReturn([]);

        $result = $this->factory->create();

        $this->assertSame([], $result->sort);
    }
}
