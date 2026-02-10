<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use JACQ\Entity\Jacq\Herbarinput\Typus;


final class IsTypusFilter implements SpecimenQueryFilter
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->onlyType === false) {
            return;
        }
        $subQb = $this->em->createQueryBuilder()
            ->select('t.id')
            ->from(Typus::class, 't')
            ->where('t.specimen = specimen.id');

        $qb->andWhere($qb->expr()->exists($subQb->getDQL()));
 //TODO cleanup which one works

//        $qb->where(
//            $qb->expr()->exists(
//                'SELECT t.id FROM JACQ\Entity\Jacq\Herbarinput\Typus t WHERE t.specimen = specimen.id'
//            )
//        );
//        $qb->innerJoin('specimen.typus', 'typus');
    }
}

