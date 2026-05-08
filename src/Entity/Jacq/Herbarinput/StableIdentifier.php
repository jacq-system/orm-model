<?php

declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Entity\Attributes\TCreatedAt;
use JACQ\Entity\Attributes\TUpdatedAt;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_specimens_stblid', schema: 'herbarinput')]
class StableIdentifier
{
    use TCreatedAt;
    use TUpdatedAt;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    public protected(set) ?int $id = null;

    #[ORM\Column(name: 'stableIdentifier')]
    public protected(set) ?string $identifier;

    #[ORM\Column]
    public protected(set) string $origin;

    #[ORM\Column(type: 'boolean')]
    public protected(set) bool $visible;

    #[ORM\Column]
    public protected(set) ?string $error;

    #[ORM\ManyToOne(targetEntity: Specimens::class, inversedBy: 'stableIdentifiers')]
    #[ORM\JoinColumn(name: 'specimen_ID', referencedColumnName: 'specimen_ID')]
    public protected(set) Specimens $specimen;

    #[ORM\ManyToOne(targetEntity: Specimens::class)]
    #[ORM\JoinColumn(name: 'blockedBy', referencedColumnName: 'specimen_ID')]
    public protected(set) ?Specimens $blockingSpecimen;

}
