<?php

declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_tax_classification', schema: 'herbarinput')]
class Classification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'classification_id')]
    public protected(set) ?int $id = null;

    #[ORM\Column(name: 'parent_taxonID')]
    public protected(set) int $parentTaxonId;

    #[ORM\Column(name: 'order')]
    public protected(set) int $sort;

    #[ORM\OneToOne(targetEntity: Synonymy::class, inversedBy: 'classification')]
    #[ORM\JoinColumn(name: 'tax_syn_ID', referencedColumnName: 'tax_syn_ID')]
    public protected(set) Synonymy $synonymy;

}
