<?php

declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\SynonymyRepository;

#[ORM\Entity(repositoryClass: SynonymyRepository::class)]
#[ORM\Table(name: 'tbl_tax_synonymy', schema: 'herbarinput')]
class Synonymy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'tax_syn_ID')]
    public protected(set) ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Literature::class)]
    #[ORM\JoinColumn(name: 'source_citationID', referencedColumnName: 'citationID')]
    public protected(set) Literature $literature;

    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[ORM\JoinColumn(name: 'taxonID', referencedColumnName: 'taxonID')]
    public protected(set) Species $species;

    #[ORM\Column(name: 'acc_taxon_ID')]
    public protected(set) ?int $actualTaxonId = null;

    #[ORM\OneToOne(targetEntity: Classification::class, mappedBy: 'synonymy')]
    public protected(set) ?Classification $classification;

}
