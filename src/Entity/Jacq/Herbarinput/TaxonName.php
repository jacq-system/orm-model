<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'tbl_tax_sciname', schema: 'herbarinput')]
class TaxonName
{
    #[ORM\Column(name: 'scientificName')]
    protected(set) string $scientificName;

    #[ORM\Column(name: 'taxonName')]
    protected(set) string $taxonName;

    #[ORM\Id]
    #[ORM\OneToOne(
        targetEntity: Species::class,
        inversedBy: 'taxonName'
    )]
    #[ORM\JoinColumn(name: 'taxonID', referencedColumnName: 'taxonID', nullable: false)]
    private ?Species $taxon = null;
}
