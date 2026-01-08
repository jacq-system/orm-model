<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_province', schema: 'herbarinput')]
class Province
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'provinceID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'provinz')]
    protected(set) string $name;

    #[ORM\Column(name: 'provinz_local')]
    protected(set) string $nameLocal;

}
