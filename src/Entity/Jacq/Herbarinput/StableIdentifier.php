<?php declare(strict_types = 1);

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

    #[ORM\Column(name: 'timestamp',type: 'datetime')]
    protected(set) DateTime $timestamp;

    #[ORM\Column]
    protected(set) ?string $error;

    #[ORM\ManyToOne(targetEntity: Specimens::class, inversedBy: 'stableIdentifier')]
    #[ORM\JoinColumn(name: 'specimen_ID', referencedColumnName: 'specimen_ID')]
    protected(set) Specimens $specimen;

    #[ORM\ManyToOne(targetEntity: Specimens::class)]
    #[ORM\JoinColumn(name: 'blockedBy', referencedColumnName: 'specimen_ID')]
    protected(set) ?Specimens $blockingSpecimen;

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getSpecimen(): Specimens
    {
        return $this->specimen;
    }

    public function getBlockingSpecimen(): ?Specimens
    {
        return $this->blockingSpecimen;
    }



}
