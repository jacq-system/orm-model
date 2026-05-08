<?php

declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\CollectorRepository;

#[ORM\Entity(repositoryClass: CollectorRepository::class)]
#[ORM\Table(name: 'tbl_collector', schema: 'herbarinput')]
class Collector
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'SammlerID')]
    public protected(set) ?int $id = null;

    #[ORM\Column(name: 'Sammler')]
    public protected(set) string $name;

    #[ORM\Column(name: 'WIKIDATA_ID')]
    public protected(set) ?string $wikidataId = null;

    #[ORM\Column(name: 'HUH_ID')]
    public protected(set) ?string $huhId = null;

    #[ORM\Column(name: 'VIAF_ID')]
    public protected(set) ?string $viafId = null;

    #[ORM\Column(name: 'ORCID')]
    public protected(set) ?string $orcidId = null;

    #[ORM\Column(name: 'Bloodhound_ID')]
    public protected(set) ?string $bloodHoundId = null;

}
