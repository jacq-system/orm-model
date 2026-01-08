<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\GbifPilot;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Entity\Jacq\Herbarinput\Specimens;

#[ORM\Entity]
#[ORM\Table(name: 'europeana_images', schema: 'gbif_pilot')]
class EuropeanaImages
{

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Specimens::class, inversedBy: 'europeanaImages')]
    #[ORM\JoinColumn(name: 'specimen_ID', referencedColumnName: 'specimen_ID')]
    protected(set) Specimens $specimen;

    #[ORM\Column(name: 'filesize')]
    protected(set) int $filesize;

}
