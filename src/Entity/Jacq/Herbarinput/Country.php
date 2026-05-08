<?php

declare(strict_types=1);

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
    public protected(set) ?int $id = null;

    #[ORM\Column(name: 'nation')]
    public protected(set) string $name;

    #[ORM\Column(name: 'nation_engl')]
    public protected(set) string $nameEng;

    #[ORM\Column(name: 'nation_deutsch')]
    public protected(set) string $nameDe;

    #[ORM\Column(name: 'language_variants')]
    public protected(set) string $variants;

    #[ORM\Column(name: 'iso_alpha_2_code')]
    public protected(set) string $isoCode2;

    #[ORM\Column(name: 'iso_alpha_3_code')]
    public protected(set) string $isoCode3;

    /**
     * @var Collection<int, Institution>
     */
    #[ORM\OneToMany(targetEntity: Institution::class, mappedBy: "country")]
    #[ORM\OrderBy(["IHDescription" => "ASC"])]
    public protected(set) Collection $institutions;
}
