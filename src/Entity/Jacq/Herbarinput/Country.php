<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_geo_nation', schema: 'herbarinput')]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'NationID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'nation')]
    protected(set) string $name;

    #[ORM\Column(name: 'nation_engl')]
    protected(set) string $nameEng;

    #[ORM\Column(name: 'nation_deutsch')]
    protected(set) string $nameDe;

    #[ORM\Column(name: 'language_variants')]
    protected(set) string $variants;

    #[ORM\Column(name: 'iso_alpha_2_code')]
    protected(set) string $isoCode2;

    #[ORM\Column(name: 'iso_alpha_3_code')]
    protected(set) string $isoCode3;

    #[ORM\OneToMany(targetEntity: Institution::class, mappedBy: "country")]
    #[ORM\OrderBy(["IHDescription" => "ASC"])]
    protected(set) Collection $institutions;
}
