<?php declare(strict_types=1);

namespace JACQ\Tests\Application\Specimen\Search\Sort;

use Doctrine\ORM\AbstractQueryBuilder;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use JACQ\Application\Specimen\Search\Sort\SpecimenSort;
use JACQ\Application\Specimen\Search\Sort\SpecimenSortEnum;
use PHPUnit\Framework\TestCase;

class SpecimenSortTest extends TestCase
{
    private SpecimenSort $sort;
    private QueryBuilder $queryBuilder;
    private SpecimenSearchJoinManager $joinManager;

    protected function setUp(): void
    {
        $this->sort = new SpecimenSort();
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->joinManager = new SpecimenSearchJoinManager();
    }

    public function testApplyWithEmptySort(): void
    {
        $parameters = new SpecimenSearchParameters(sort: []);
        
        $this->queryBuilder->expects($this->once())
            ->method('resetDQLPart')
            ->with('orderBy')
            ->willReturn($this->queryBuilder);
        
        $this->sort->apply($this->queryBuilder, $this->joinManager, $parameters);
    }

    public function testApplyWithScientificNameSort(): void
    {
        $parameters = new SpecimenSearchParameters(sort: [SpecimenSortEnum::SCIENTIFIC_NAME->value => 'ASC']);
        
        $this->queryBuilder->expects($this->once())
            ->method('resetDQLPart')
            ->with('orderBy')
            ->willReturn($this->queryBuilder);
        
        $this->queryBuilder->expects($this->exactly(2))
            ->method('addOrderBy')
            ->willReturnOnConsecutiveCalls(
                $this->queryBuilder,
                $this->queryBuilder
            );
        
        $this->sort->apply($this->queryBuilder, $this->joinManager, $parameters);
    }

    public function testApplyWithCollectorSort(): void
    {
        $parameters = new SpecimenSearchParameters(sort: [SpecimenSortEnum::COLLECTOR->value => 'DESC']);
        
        $this->queryBuilder->expects($this->once())
            ->method('resetDQLPart')
            ->willReturn($this->queryBuilder);
        
        $this->queryBuilder->expects($this->exactly(2))
            ->method('addOrderBy')
            ->willReturnOnConsecutiveCalls(
                $this->queryBuilder,
                $this->queryBuilder
            );
        
        $this->sort->apply($this->queryBuilder, $this->joinManager, $parameters);
    }

    public function testApplyWithInvalidDirectionDefaultsToAsc(): void
    {
        $parameters = new SpecimenSearchParameters(sort: [SpecimenSortEnum::DATE->value => 'INVALID']);
        
        $this->queryBuilder->expects($this->once())
            ->method('resetDQLPart')
            ->willReturn($this->queryBuilder);
        
        $this->queryBuilder->expects($this->exactly(2))
            ->method('addOrderBy')
            ->willReturnOnConsecutiveCalls(
                $this->queryBuilder,
                $this->queryBuilder
            );
        
        $this->sort->apply($this->queryBuilder, $this->joinManager, $parameters);
    }

    public function testApplyWithUnknownColumnSkipsSorting(): void
    {
        $parameters = new SpecimenSearchParameters(sort: ['unknown_column' => 'ASC']);
        
        $this->queryBuilder->expects($this->once())
            ->method('resetDQLPart')
            ->willReturn($this->queryBuilder);
        
        $this->queryBuilder->expects($this->once())
            ->method('addOrderBy')
            ->with('specimen.id', 'ASC');
        
        $this->sort->apply($this->queryBuilder, $this->joinManager, $parameters);
    }
}