<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use DateTime;
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
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'stableIdentifier')]
    protected(set) ?string $identifier;

    #[ORM\Column]
    protected(set) string $origin;

    #[ORM\Column(type: 'boolean')]
    protected(set) bool $visible;

    #[ORM\Column]
    protected(set) ?string $error;

    #[ORM\ManyToOne(targetEntity: Specimens::class, inversedBy: 'stableIdentifier')]
    #[ORM\JoinColumn(name: 'specimen_ID', referencedColumnName: 'specimen_ID')]
    protected(set) Specimens $specimen;

    #[ORM\ManyToOne(targetEntity: Specimens::class)]
    #[ORM\JoinColumn(name: 'blockedBy', referencedColumnName: 'specimen_ID')]
    protected(set) ?Specimens $blockingSpecimen;

}
