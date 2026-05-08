<?php declare(strict_types=1);

namespace JACQ\Tests\UI\Http;

use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use JACQ\UI\Http\SpecimenSearchParametersFromRequestFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class SpecimenSearchParametersFromRequestFactoryTest extends TestCase
{
    private SpecimenSearchParametersFromRequestFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new SpecimenSearchParametersFromRequestFactory();
    }

    public function testCreateWithEmptyRequest(): void
    {
        $request = new Request();

        $result = $this->factory->create($request);

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

    public function testCreateWithAllParameters(): void
    {
        $request = new Request([
            'institution' => '1',
            'herbNr' => 'HERB-001',
            'collection' => '2',
            'collectorNr' => 'CN-001',
            'collector' => 'Smith',
            'collectionDate' => '2024-01-01',
            'collectionNr' => 'COLL-001',
            'series' => 'Series A',
            'locality' => 'Vienna',
            'habitus' => 'Tree',
            'habitat' => 'Forest',
            'taxonAlternative' => 'Alternative Taxon',
            'annotation' => 'Annotated',
            'country' => 'Austria',
            'province' => 'Lower Austria',
            'onlyType' => '1',
            'includeSynonym' => '1',
            'onlyImages' => '1',
            'family' => 'Rosaceae',
            'onlyCoords' => '1',
            'taxon' => 'Rosa',
        ]);

        $result = $this->factory->create($request);

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
    }

    public function testCreateWithInstitutionZero(): void
    {
        $request = new Request([], ['institution' => '0']);

        $result = $this->factory->create($request);

        $this->assertNull($result->institution);
    }

    public function testCreateWithCollectionZero(): void
    {
        $request = new Request([], ['collection' => '0']);

        $result = $this->factory->create($request);

        $this->assertNull($result->collection);
    }

    public function testCreateFromLegacyWithEmptyRequest(): void
    {
        $request = new Request();

        $result = $this->factory->createFromLegacy($request);

        $this->assertInstanceOf(SpecimenSearchParameters::class, $result);
        $this->assertNull($result->institutionCode);
        $this->assertNull($result->herbNr);
        $this->assertNull($result->collector);
        $this->assertNull($result->country);
        $this->assertFalse($result->onlyType);
        $this->assertFalse($result->onlyImages);
        $this->assertNull($result->taxon);
        $this->assertSame([], $result->sort);
    }

    public function testCreateFromLegacyWithAllParameters(): void
    {
        $request = new Request([
            'sc' => 'W',
            'herbnr' => 'W-001',
            'coll' => 'Collector Name',
            'nation' => 'Austria',
            'type' => '1',
            'withImages' => '1',
            'term' => 'Rosa canina',
            'sort' => '+name,-date',
        ]);

        $result = $this->factory->createFromLegacy($request);

        $this->assertSame('W', $result->institutionCode);
        $this->assertSame('W-001', $result->herbNr);
        $this->assertSame('Collector Name', $result->collector);
        $this->assertSame('Austria', $result->country);
        $this->assertTrue($result->onlyType);
        $this->assertTrue($result->onlyImages);
        $this->assertSame('Rosa canina', $result->taxon);
        $this->assertSame(['name' => 'ASC', 'date' => 'DESC'], $result->sort);
    }

    public function testCreateFromLegacyWithSortAscending(): void
    {
        $request = new Request(['sort' => '+name']);

        $result = $this->factory->createFromLegacy($request);

        $this->assertSame(['name' => 'ASC'], $result->sort);
    }

    public function testCreateFromLegacyWithSortDescending(): void
    {
        $request = new Request(['sort' => '-name']);

        $result = $this->factory->createFromLegacy($request);

        $this->assertSame(['name' => 'DESC'], $result->sort);
    }

    public function testCreateFromLegacyWithSortNoPrefix(): void
    {
        $request = new Request(['sort' => 'name']);

        $result = $this->factory->createFromLegacy($request);

        $this->assertSame(['name' => ''], $result->sort);
    }

    public function testCreateFromLegacyWithMultipleSortParts(): void
    {
        $request = new Request(['sort' => '+name, -date, location']);

        $result = $this->factory->createFromLegacy($request);

        $this->assertSame([
            'name' => 'ASC',
            'date' => 'DESC',
            'location' => '',
        ], $result->sort);
    }

    public function testCreateFromLegacyWithFalseValues(): void
    {
        $request = new Request([], [
            'type' => '0',
            'withImages' => '0',
        ]);

        $result = $this->factory->createFromLegacy($request);

        $this->assertFalse($result->onlyType);
        $this->assertFalse($result->onlyImages);
    }
}