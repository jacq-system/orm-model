<?php

declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_tax_families', schema: 'herbarinput')]
class Family
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'familyID')]
    public protected(set) ?int $id = null;

    #[ORM\Column(name: 'family')]
    public protected(set) string $name;

    #[ORM\Column(name: 'family_alt')]
    public protected(set) string $nameAlternative;

}
