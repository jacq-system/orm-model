<?php declare(strict_types=1);

namespace JACQ\Repository\Herbarinput;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JACQ\Entity\Jacq\Herbarinput\Country;
use JACQ\Entity\Jacq\Herbarinput\Institution;


class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function findWithInstitutions(): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin(
                Institution::class,
                'i',
                'WITH',
                'i.country = c'
            )
            ->orderBy('c.nameEng', 'ASC')
            ->getQuery()
            ->getResult();
    }


}
