<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_specimens_types', schema: 'herbarinput')]
class Typus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'specimens_types_ID')]
    protected(set) ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Specimens::class, inversedBy: 'typus')]
    #[ORM\JoinColumn(name: 'specimenID', referencedColumnName: 'specimen_ID')]
    protected(set) Specimens $specimen;

    #[ORM\ManyToOne(targetEntity: TypusRank::class)]
    #[ORM\JoinColumn(name: 'typusID', referencedColumnName: 'typusID')]
    protected(set) TypusRank $rank;

    #[ORM\ManyToOne(targetEntity: Species::class)]
    #[ORM\JoinColumn(name: 'taxonID', referencedColumnName: 'taxonID')]
    protected(set) Species $species;

    #[ORM\Column(name: 'typified_by_Person ')]
    protected(set) string $person;

    #[ORM\Column(name: 'typified_Date')]
    protected(set) string $date;

}
