<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use JACQ\Entity\Jacq\Herbarinput\Typus;


final class IsTypusFilter implements SpecimenQueryFilter
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->onlyType === false) {
            return;
        }

        //using this approach not to block Doctrine toIterable
        $subQb = $this->em->createQueryBuilder()
            ->select('t.id')
            ->from(Typus::class, 't')
            ->where('t.specimen = specimen.id');

        $qb->andWhere($qb->expr()->exists($subQb->getDQL()));

    }
}

