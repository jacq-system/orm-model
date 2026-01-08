<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_typi', schema: 'herbarinput')]
class TypusRank
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'typusID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'typus_lat')]
    protected(set) string $latinName;

}
