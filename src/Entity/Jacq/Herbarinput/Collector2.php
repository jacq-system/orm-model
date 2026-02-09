<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\Collector2Repository;

#[ORM\Entity(repositoryClass: Collector2Repository::class)]
#[ORM\Table(name: 'tbl_collector_2', schema: 'herbarinput')]
class Collector2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Sammler_2ID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'Sammler_2')]
    protected(set) string $name;

}
