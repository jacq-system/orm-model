<?php declare(strict_types=1);

namespace JACQ\Service;


use Doctrine\ORM\EntityManagerInterface;
use JACQ\Entity\Jacq\Herbarinput\HerbCollection;
use JACQ\Entity\Jacq\HerbarPictures\IiifDefinition;

readonly class HerbCollectionService extends BaseService
{


    public function __construct(EntityManagerInterface $entityManager, JacqNetworkService $jacqNetworkService)
    {
        parent::__construct($entityManager, $jacqNetworkService);
    }

    public function getIiifDefiniton(HerbCollection $herbCollection): ?IiifDefinition
    {
        return $this->entityManager->getRepository(IiifDefinition::class)->createQueryBuilder('imgdef')
            ->where('imgdef.institution = :institution')
            ->setParameter('institution', $herbCollection->institution->id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
