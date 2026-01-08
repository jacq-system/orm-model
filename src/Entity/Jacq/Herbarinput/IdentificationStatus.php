<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_specimens_identstatus', schema: 'herbarinput')]
class IdentificationStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'identstatusID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'identification_status')]
    protected(set) string $name;

}
