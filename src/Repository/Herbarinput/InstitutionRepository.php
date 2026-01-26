<?php declare(strict_types=1);

namespace JACQ\Repository\Herbarinput;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JACQ\Entity\Jacq\Herbarinput\Institution;


class InstitutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Institution::class);
    }

    public function getAllPairsCodeName(): array
    {

        $qb = $this->createQueryBuilder('i')
            ->select('DISTINCT i.id AS id, CONCAT(i.code, \' - \', i.name2) AS name')
            ->join('i.collections', 'c')
            ->join('c.specimens', 's')
            ->orderBy('name');
        $results = $qb->getQuery()->getArrayResult();
        return array_column($results, 'name', 'id');

    }

    public function getWithCoords(): array
    {
        $sql = "SELECT i.*, ST_X(i.coords) AS lon, ST_Y(i.coords) AS lat
        FROM herbarinput.metadata i
        WHERE i.coords IS NOT NULL";

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        return  $stmt->fetchAllAssociative();

    }
}
