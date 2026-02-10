<?php declare(strict_types=1);

namespace JACQ\Application\Specimen\Search\Filter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use JACQ\Application\Specimen\Search\SpecimenSearchParameters;


final class TaxonFilter implements SpecimenQueryFilter
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function apply(QueryBuilder $qb, SpecimenSearchParameters $parameters): void
    {
        if ($parameters->taxon === null) {
            return;
        }

        $taxaIds = $this->getTaxonIds($parameters->taxon);
        $conditions = [];
        if (empty($taxaIds)) {
            $qb->andWhere('1 = 0');
        }

        //result includes NULL rows that need to be excluded
        $taxonId = array_filter(array_column($taxaIds, 'taxonID'), fn($value) => $value !== null);
        $basID = array_filter(array_column($taxaIds, 'basID'), fn($value) => $value !== null);
        $synID = array_filter(array_column($taxaIds, 'synID'), fn($value) => $value !== null);
        if (!empty($parameters->includeSynonym)) {
            if (!empty($taxonId)) {
                $conditions[] = $qb->expr()->orX(
                    $qb->expr()->in('species.id', $taxonId),
                    $qb->expr()->in('species.basionym', $taxonId),
                    $qb->expr()->in('species.validName', $taxonId)
                );
            }

            if (!empty($basID)) {
                $conditions[] = $qb->expr()->orX(
                    $qb->expr()->in('species.id', $basID),
                    $qb->expr()->in('species.basionym', $basID),
                    $qb->expr()->in('species.validName', $basID)
                );
            }

            if (!empty($synID)) {
                $conditions[] = $qb->expr()->orX(
                    $qb->expr()->in('species.id', $synID),
                    $qb->expr()->in('species.basionym', $synID),
                    $qb->expr()->in('species.validName', $synID)
                );
            }
        } else {
            if (!empty($taxonId)) {
                $conditions[] = $qb->expr()->orX(
                    $qb->expr()->in('species.id', $taxonId)
                );
            }
        }

        //finally add to the builder
        $qb
            ->andWhere(
                $qb->expr()->orX(...$conditions)
            );
    }

    protected function getTaxonIds(string $name): array
    {
        $pieces = explode(" ", trim($name));
        $part1 = array_shift($pieces);
        $part2 = array_shift($pieces);
        if (empty($part2)) {
            $sql = "SELECT ts.taxonID, ts.basID, ts.synID
                    FROM tbl_tax_genera tg,  tbl_tax_species ts
                     LEFT JOIN tbl_tax_epithets te ON te.epithetID = ts.speciesID
                     LEFT JOIN tbl_tax_epithets te1 ON te1.epithetID = ts.subspeciesID
                     LEFT JOIN tbl_tax_epithets te2 ON te2.epithetID = ts.varietyID
                     LEFT JOIN tbl_tax_epithets te3 ON te3.epithetID = ts.subvarietyID
                     LEFT JOIN tbl_tax_epithets te4 ON te4.epithetID = ts.formaID
                     LEFT JOIN tbl_tax_epithets te5 ON te5.epithetID = ts.subformaID
                    WHERE tg.genID = ts.genID AND tg.genus LIKE :part1 ";
        } else {
            $sql = "SELECT ts.taxonID, ts.basID, ts.synID
                    FROM tbl_tax_genera tg,  tbl_tax_species ts
                     LEFT JOIN tbl_tax_epithets te ON te.epithetID = ts.speciesID
                     LEFT JOIN tbl_tax_epithets te1 ON te1.epithetID = ts.subspeciesID
                     LEFT JOIN tbl_tax_epithets te2 ON te2.epithetID = ts.varietyID
                     LEFT JOIN tbl_tax_epithets te3 ON te3.epithetID = ts.subvarietyID
                     LEFT JOIN tbl_tax_epithets te4 ON te4.epithetID = ts.formaID
                     LEFT JOIN tbl_tax_epithets te5 ON te5.epithetID = ts.subformaID
                    WHERE tg.genID = ts.genID AND tg.genus LIKE :part1  AND (     te.epithet LIKE :part2
                                                        OR te1.epithet LIKE :part2
                                                        OR te2.epithet LIKE :part2
                                                        OR te3.epithet LIKE :part2
                                                        OR te4.epithet LIKE :part2
                                                        OR te5.epithet LIKE :part2)";
        }

        return $this->em->getConnection()->executeQuery($sql, ['part1' => $part1 . '%', 'part2' => $part2 . '%'])->fetchAllAssociative();
    }
}

