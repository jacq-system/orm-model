<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_province_boundaries', schema: 'herbarinput')]
class GeoProvinceBoundaries
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    protected(set) ?int $id = null;

    #[ORM\Column(type: 'integer', name: 'provinceID')]
    protected(set) int $provinceId;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected(set) ?string $annotation = null;

    #[ORM\Column(type: 'float')]
    protected(set) float $boundSouth;

    #[ORM\Column(type: 'float')]
    protected(set) float $boundNorth;

    #[ORM\Column(type: 'float')]
    protected(set) float $boundEast;

    #[ORM\Column(type: 'float')]
    protected(set) float $boundWest;

}
