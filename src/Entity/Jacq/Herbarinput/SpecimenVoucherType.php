<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_specimens_voucher', schema: 'herbarinput')]
class SpecimenVoucherType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'voucherID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'voucher')]
    protected(set) ?string $name;

}
