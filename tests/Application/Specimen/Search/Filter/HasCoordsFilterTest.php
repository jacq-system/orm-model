<?php

declare(strict_types=1);

namespace JACQ\Tests\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\Filter\HasCoordsFilter;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use PHPUnit\Framework\TestCase;

class HasCoordsFilterTest extends TestCase
{
    private HasCoordsFilter $filter;
    private QueryBuilder $queryBuilder;
    private SpecimenSearchJoinManager $joinManager;

    protected function setUp(): void
    {
        $this->filter = new HasCoordsFilter();
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->joinManager = new SpecimenSearchJoinManager();
    }

    public function testApplyWhenOnlyCoordsIsFalse(): void
    {
        $parameters = new SpecimenSearchParameters(onlyCoords: false);

        $this->queryBuilder->expects($this->never())
            ->method('andWhere');

        $this->filter->apply($this->queryBuilder, $this->joinManager, $parameters);
    }

    public function testApplyWhenOnlyCoordsIsTrue(): void
    {
        $parameters = new SpecimenSearchParameters(onlyCoords: true);

        $this->queryBuilder->expects($this->once())
            ->method('andWhere');

        $this->filter->apply($this->queryBuilder, $this->joinManager, $parameters);
    }

    public function testApplyDoesNotAddConditionTwice(): void
    {
        $parameters = new SpecimenSearchParameters(onlyCoords: true);

        $this->queryBuilder->expects($this->once())
            ->method('andWhere');

        $this->filter->apply($this->queryBuilder, $this->joinManager, $parameters);

        // Second call should not add condition again (flag is set)
        $this->filter->apply($this->queryBuilder, $this->joinManager, $parameters);
    }
}
