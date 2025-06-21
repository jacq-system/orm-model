<?php declare(strict_types = 1);

namespace JACQ\Entity\Jacq\HerbarPictures;

use JACQ\Entity\Jacq\Herbarinput\Specimens;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'phaidra_cache', schema: 'herbar_pictures')]
class PhaidraCache
{

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Specimens::class, inversedBy: 'phaidraImages')]
    #[ORM\JoinColumn(name: 'specimenID', referencedColumnName: 'specimen_ID')]
    private Specimens $specimen;



}
