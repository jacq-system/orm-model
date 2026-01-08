<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\HerbarPictures;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Entity\Jacq\Herbarinput\Specimens;

#[ORM\Entity]
#[ORM\Table(name: 'phaidra_cache', schema: 'herbar_pictures')]
class PhaidraCache
{

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Specimens::class, inversedBy: 'phaidraImages')]
    #[ORM\JoinColumn(name: 'specimenID', referencedColumnName: 'specimen_ID')]
    protected(set) Specimens $specimen;

}
