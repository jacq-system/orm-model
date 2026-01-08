<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_specimens_links', schema: 'herbarinput')]
class SpecimenLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'specimens_linkID')]
    protected(set) ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Specimens::class, inversedBy: 'outgoingRelations')]
    #[ORM\JoinColumn(name: 'specimen1_ID', referencedColumnName: 'specimen_ID', nullable: false)]
    protected(set) Specimens $specimen1;

    #[ORM\ManyToOne(targetEntity: Specimens::class, inversedBy: 'incomingRelations')]
    #[ORM\JoinColumn(name: 'specimen2_ID', referencedColumnName: 'specimen_ID', nullable: false)]
    protected(set) Specimens $specimen2;

    #[ORM\ManyToOne(targetEntity: SpecimenLinkQualifier::class)]
    #[ORM\JoinColumn(name: 'link_qualifierID', referencedColumnName: 'link_qualifierID', nullable: true)]
    protected(set) ?SpecimenLinkQualifier $linkQualifier;

}
