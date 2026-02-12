<?php declare(strict_types=1);

namespace JACQ\Repository\Herbarinput;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JACQ\Entity\Jacq\Herbarinput\ImageDefinition;
use JACQ\Entity\Jacq\Herbarinput\Institution;


class ImageDefinitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageDefinition::class);
    }

    /**
     * @param string $institutionID
     * @return ImageDefinition[]
     */
    public function getDjatokaServers(?int $institutionID): array
    {
        $qb = $this->createQueryBuilder('img')
            ->select('img')
            ->join('img.institution', 'i')
            ->andWhere('img.serverType = :djatoka')
            ->andWhere('img.iiifCapable != true') //iiif-servers are not checked
            ->andWhere('i.id != :wu') // wu need special treatment
            ->orderBy('img.abbreviation', 'ASC')
            ->setParameter('djatoka', 'djatoka')
            ->setParameter('wu', Institution::WU);

        if ($institutionID !== null) {
            $qb->andWhere('i.id = :institutionID')
                ->setParameter('institutionID', $institutionID);
        }

        return $qb->getQuery()->getResult();

    }

    public function getImageDefiniton(Institution $institution): ?ImageDefinition
    {
        return $this->createQueryBuilder()
            ->select('imd')
            ->from(ImageDefinition::class, 'imd')
            ->where('imd.institution = :institution')
            ->setParameter('institution', $institution)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
