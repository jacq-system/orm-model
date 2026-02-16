<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Entity\Jacq\Herbarinput\Specimens;

final readonly class SpecimenBatchProvider
{
    public function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    public function iterate(
        QueryBuilder $queryBuilder,
        int          $offset,
        int          $limit,
        int          $batchSize = 50,
        bool         $returnEntities = true,
    ): \Generator
    {
        $processed = 0;;

        while ($processed < $limit) {
            $qb = clone $queryBuilder;

            $ids = $qb
                ->setFirstResult($offset + $processed)
                ->setMaxResults(min($batchSize, $limit - $processed))
                ->getQuery()
                ->getScalarResult();

            if (!$ids) {
                break;
            }

            $specimenIds = array_column($ids, 'id');
            if ($returnEntities) {
                $specimens = $this->em
                    ->getRepository(Specimens::class)
                    ->createQueryBuilder('specimen')
                    ->select('DISTINCT specimen')
                    ->leftJoin('specimen.species', 'species')
                    ->addSelect('species')
                    ->leftJoin('species.materializedName', 'materializedName')
                    ->addSelect('materializedName')
                    ->leftJoin('species.epithetSpecies', 'epithetSpecies')
                    ->addSelect('epithetSpecies')
                    ->leftJoin('species.authorSpecies', 'authorSpecies')
                    ->addSelect('authorSpecies')
                    ->leftJoin('species.genus', 'genus')
                    ->addSelect('genus')
                    ->leftJoin('genus.family', 'family')
                    ->addSelect('family')
                    ->leftJoin('specimen.herbCollection', 'collection')
                    ->addSelect('collection')
                    ->leftJoin('specimen.collector', 'collector')
                    ->addSelect('collector')
                    ->leftJoin('specimen.collector2', 'collector2')
                    ->addSelect('collector2')
                    ->leftJoin('specimen.country', 'country')
                    ->addSelect('country')
                    ->leftJoin('specimen.province', 'province')
                    ->addSelect('province')
                    ->where('specimen.id IN (:ids)')
                    ->setParameter('ids', $specimenIds)
                    ->getQuery()
                    ->getResult();

                $orderMap = array_flip($specimenIds); //we have a required order for every specimen ID
                usort($specimens, static fn($a, $b) => $orderMap[$a->id] <=> $orderMap[$b->id]); //sort results according the order - it is faster to do in memory than again in the database

                foreach ($specimens as $specimen) {
                    yield $specimen;
                    $processed++;
                }
            } else {
                // yield only IDs
                foreach ($specimenIds as $id) {
                    yield $id;
                    $processed++;
                }
            }
            $this->em->clear();

        }
    }
}