<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_specimens_series', schema: 'herbarinput')]
class Series
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'seriesID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'series')]
    protected(set) string $name;

}
