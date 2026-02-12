<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Entity\Jacq\Herbarinput\Specimens;

final readonly class SpecimenBatchProvider
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function iterate(QueryBuilder $queryBuilder, int $limit, int $batchSize = 50): \Generator
    {
        $lastId = 0;
        $processed = 0;

        while ($processed < $limit) {
            $qb = clone $queryBuilder;

            $ids = $qb
                ->andWhere('specimen.id > :lastId')
                ->setParameter('lastId', $lastId)
                ->setMaxResults($batchSize)
                ->getQuery()
                ->getScalarResult();

            if (!$ids) {
                break;
            }

            $specimenIds = array_column($ids, 'id');

            $specimens = $this->em
                ->getRepository(Specimens::class)
                ->createQueryBuilder('specimen')
                ->where('specimen.id IN (:ids)')
                ->setParameter('ids', $specimenIds)
                ->getQuery()
                ->getResult();

            foreach ($specimens as $specimen) {
                yield $specimen;

                $processed++;
                $lastId = $specimen->id;

                if ($processed >= $limit) {
                    break 2;
                }
            }

            $this->em->clear();
        }
    }
}
