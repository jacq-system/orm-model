<?php declare(strict_types = 1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_specimens_links_qualifiers', schema: 'herbarinput')]
class SpecimenLinkQualifier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'link_qualifierID')]
    private ?int $id = null;

    #[ORM\Column(name: 'SpecimenQualifier_engl')]
    private string $name;

    #[ORM\Column(name: 'SpecimenQualifier_reverse')]
    private string $nameReverse;

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameReverse(): string
    {
        return $this->nameReverse;
    }

}
