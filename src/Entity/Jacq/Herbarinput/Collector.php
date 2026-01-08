<?php declare(strict_types=1);

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
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'Sammler')]
    protected(set) string $name;

    #[ORM\Column(name: 'WIKIDATA_ID')]
    protected(set) ?string $wikidataId = null;

    #[ORM\Column(name: 'HUH_ID')]
    protected(set) ?string $huhId = null;

    #[ORM\Column(name: 'VIAF_ID')]
    protected(set) ?string $viafId = null;

    #[ORM\Column(name: 'ORCID')]
    protected(set) ?string $orcidId = null;

    #[ORM\Column(name: 'Bloodhound_ID')]
    protected(set) ?string $bloodHoundId = null;

}
