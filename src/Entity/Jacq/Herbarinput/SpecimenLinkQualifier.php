<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_specimens_links_qualifiers', schema: 'herbarinput')]
class SpecimenLinkQualifier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'link_qualifierID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'SpecimenQualifier_engl')]
    protected(set) string $name;

    #[ORM\Column(name: 'SpecimenQualifier_reverse')]
    protected(set) string $nameReverse;

}
