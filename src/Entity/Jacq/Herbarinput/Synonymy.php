<?php declare(strict_types=1);

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
    protected(set) ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Literature::class)]
    #[ORM\JoinColumn(name: 'source_citationID', referencedColumnName: 'citationID')]
    protected(set) Literature $literature;

    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[ORM\JoinColumn(name: 'taxonID', referencedColumnName: 'taxonID')]
    protected(set) Species $species;

    #[ORM\Column(name: 'acc_taxon_ID')]
    protected(set) ?int $actualTaxonId = null;

    #[ORM\OneToOne(targetEntity: Classification::class, mappedBy: 'synonymy')]
    protected(set) ?Classification $classification;

}
