<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_tax_epithets', schema: 'herbarinput')]
class Epithet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'epithetID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'epithet')]
    protected(set) string $name;

}
