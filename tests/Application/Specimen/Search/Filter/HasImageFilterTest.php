<?php declare(strict_types=1);

namespace JACQ\Tests\Application\Specimen\Search\Filter;

use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\Filter\HasImageFilter;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use PHPUnit\Framework\TestCase;

class HasImageFilterTest extends TestCase
{
    private HasImageFilter $filter;
    private QueryBuilder $queryBuilder;
    private SpecimenSearchJoinManager $joinManager;

    protected function setUp(): void
    {
        $this->filter = new HasImageFilter();
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->joinManager = new SpecimenSearchJoinManager();
    }

    public function testApplyWhenOnlyImagesIsFalse(): void
    {
        $parameters = new SpecimenSearchParameters(onlyImages: false);
        
        $this->queryBuilder->expects($this->never())
            ->method('andWhere');
        
        $this->filter->apply($this->queryBuilder, $this->joinManager, $parameters);
    }

    public function testApplyWhenOnlyImagesIsTrue(): void
    {
        $parameters = new SpecimenSearchParameters(onlyImages: true);
        
        $this->queryBuilder->expects($this->once())
            ->method('andWhere');
        
        $this->filter->apply($this->queryBuilder, $this->joinManager, $parameters);
    }
}