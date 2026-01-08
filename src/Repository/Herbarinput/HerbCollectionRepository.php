<?php declare(strict_types=1);

namespace JACQ\Repository\Herbarinput;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use JACQ\Entity\Jacq\Herbarinput\HerbCollection;


class HerbCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HerbCollection::class);
    }

    public function getAllAsPairs(?int $herbariumAbbreviation = null): array
    {

        return array_column($this->queryByHerbarium($herbariumAbbreviation)->getArrayResult(), 'name', 'id');

    }

    private function queryByHerbarium(?int $herbariumAbbreviation = null): Query
    {
        $qb = $this->createQueryBuilder('h')->select('h.id, h.name')->join('h.specimens', 's')->groupBy('h.id')->orderBy('h.name');

        if ($herbariumAbbreviation !== null) {
            $qb->join('h.institution', 'i')->where('i.id = :herbarium')->setParameter(':herbarium', $herbariumAbbreviation);
        }
        return $qb->getQuery();
    }

    public function getAllAsObjectPairs(?int $herbariumAbbreviation = null): array
    {
        return $this->queryByHerbarium($herbariumAbbreviation)->getResult();
    }

}
