<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\Filter\SpecimenQueryFilter;
use JACQ\Entity\Jacq\Herbarinput\Specimens;

final readonly class SpecimenSearchQuery
{
    /**
     * @param SpecimenQueryFilter[] $filters
     */
    public function __construct(
        private EntityManagerInterface $em,
        private array                  $filters
    )
    {
    }

    public function countResults(SpecimenSearchParameters $parameters): int
    {
        $qb = $this->build($parameters);
        return $qb->resetDQLPart('orderBy')->select('count(DISTINCT specimen.id)')->getQuery()->getSingleScalarResult();
    }

    public function build(SpecimenSearchParameters $parameters): QueryBuilder
    {
        $qb = $this->em->getRepository(Specimens::class)
            ->createQueryBuilder('specimen')
            ->select('specimen.id')
            ->orderBy('specimen.id', 'ASC')
            ->join('specimen.species', 'species')
            ->join('species.genus', 'genus')
            ->leftJoin('species.authorSpecies', 'author')
            ->leftJoin('species.epithetSpecies', 'epithet')
            ->join('specimen.herbCollection', 'collection');

        foreach ($this->filters as $filter) {
            $filter->apply($qb, $parameters);
        }

        return $qb;
    }
}
