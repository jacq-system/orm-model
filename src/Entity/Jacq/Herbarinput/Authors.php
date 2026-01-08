<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_tax_authors', schema: 'herbarinput')]
class Authors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'authorID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'author')]
    protected(set) string $name;

}
