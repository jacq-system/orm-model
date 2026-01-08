<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\TaxonRankRepository;

#[ORM\Entity(repositoryClass: TaxonRankRepository::class)]
#[ORM\Table(name: 'tbl_tax_rank', schema: 'herbarinput')]
class TaxonRank
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'tax_rankID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'rank')]
    protected(set) string $name;

    #[ORM\Column(name: 'rank_abbr')]
    protected(set) ?string $abbreviation;

    #[ORM\Column(name: 'rank_hierarchy')]
    protected(set) int $hierarchy;

}
