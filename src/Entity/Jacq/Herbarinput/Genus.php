<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_tax_genera', schema: 'herbarinput')]
class Genus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'genID')]
    protected(set) ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Family::class)]
    #[ORM\JoinColumn(name: 'familyID', referencedColumnName: 'familyID')]
    protected(set) Family $family;

    #[ORM\Column(name: 'genus')]
    protected(set) string $name;

}
