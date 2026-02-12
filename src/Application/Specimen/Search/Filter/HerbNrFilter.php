<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchJoinManager;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;
use JACQ\Entity\Jacq\Herbarinput\Institution;
use JACQ\Entity\Jacq\Herbarinput\Specimens;

/**
 * complex due the variability of data (see https://github.com/jacq-system/herbarium-output/issues/36)
 */
final class HerbNrFilter implements SpecimenQueryFilter
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

        public function apply(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->herbNr === null) {
            return;
        }

        $pattern = '/^(?<code>[a-zA-Z]+)\s*(?<rest>.*)$/';
        if (!preg_match($pattern, $parameters->herbNr, $matches)) {
            //only number
            $qb->andWhere('specimen.herbNumber LIKE :herbNr');
            $qb->setParameter('herbNr', '%' . $parameters->herbNr . '%');
            return;
        }
        $ids = $this->queryHerbNrSeekCandidates($qb, $joinManager, $parameters, $matches['code'], $matches['rest']);
        if (count($ids) > 0) {
            //simple fulltext had found specimen(s)
            $qb->andWhere('specimen.id IN (:herbNrIds)');
            $qb->setParameter('herbNrIds', $ids, ArrayParameterType::INTEGER);
            return;
        }

        $this->fallbackToLegacy($qb, $matches['rest']);

    }

    protected function queryHerbNrSeekCandidates(QueryBuilder $qb, SpecimenSearchJoinManager $joinManager, SpecimenSearchParameters $parameters, string $code, string $rest): array
    {
        $subquery = $this->em
            ->getRepository(Specimens::class)
            ->createQueryBuilder('s')
            ->select('s.id');

        if (empty($rest)) {
            $subquery = $subquery->andWhere('s.herbNumber LIKE :herbNrFull')
                ->setParameter('herbNrFull', $parameters->herbNr . '%');
        } else {

            $subquery = $subquery->andWhere(
                $subquery->expr()->orX(
                    's.herbNumber LIKE :herbNrRest',
                    's.herbNumber LIKE :herbNrFull'
                )
            )
                ->setParameter('herbNrRest', $rest . '%')
                ->setParameter('herbNrFull', $parameters->herbNr . '%');
        }

        if (empty($parameters->institution)) {
            $institution = $this->em->getRepository(Institution::class)->findOneBy(['code' => $code]);
            /**
             * TODO this is breaking filter isolation, applying InstitutionFilter manually
             * ideally moved to a resolver of dependencies before hand
             */
            if ($institution !== null) {
                $joinManager->leftJoin($qb, 'specimen.herbCollection','collection');
                $joinManager->leftJoin($qb, 'collection.institution','institution');
                $qb
                    ->andWhere('institution.id = :institution')
                    ->setParameter('institution', $institution->id);
                $subquery = $subquery
                    ->join('s.herbCollection', 'c')
                    ->join('collection.institution', 'institution')
                    ->andWhere('institution.id = :institution')
                    ->setParameter('institution', $institution->id);
            }
        }

        return $subquery->getQuery()->getSingleColumnResult();
    }

    protected function fallbackToLegacy(QueryBuilder $qb, $endOfString): void
    {
        $rest = trim($endOfString);
        $trailing = '';
        if (ctype_alpha(substr($rest, -1))) {
            for ($i = strlen($rest) - 2; $i >= 0; $i--) {
                $checkChar = $rest[$i];
                if (!ctype_alpha($checkChar) && $checkChar !== '-') {
                    break;
                }
            }
            $trailing = substr($rest, $i + 1);
            $rest = substr($rest, 0, $i + 1);
        }

        $prefix = '';
        if (strpos($rest, '-') === 4) {// contents of search is ####-#... so, look also inside "CollNummer" (relevant for source-ID 6 = W)
            $prefix = substr($rest, 0, 5); // 1234-
            $rest = substr($rest, 5);
        }

        $number = (strlen($rest) >= 6) ? $rest : sprintf('%06d', (int)$rest);

        $like = $prefix . '%' . $number . $trailing;
        //-------------------
        $qb->setParameter('herbNr', $like);

        if (!empty($prefix)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('specimen.herbNumber', ':herbNr'),
                    $qb->expr()->like('specimen.collectionNumber', ':herbNr')
                ));
        } else {
            $qb->andWhere('specimen.herbNumber LIKE :herbNr');
        }
    }
}

