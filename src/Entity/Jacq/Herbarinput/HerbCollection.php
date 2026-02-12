<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\HerbCollectionRepository;

#[ORM\Entity(repositoryClass: HerbCollectionRepository::class)]
#[ORM\Table(name: 'tbl_management_collections', schema: 'herbarinput')]
class HerbCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'collectionID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'collection')]
    protected(set) string $name;

    #[ORM\Column(name: 'coll_short_prj')]
    protected(set) string $collShortPrj;

    #[ORM\Column(name: 'coll_short')]
    protected(set) string $collShort;

    #[ORM\Column(name: 'picture_filename')]
    protected(set) ?string $pictureFilename = null;

    #[ORM\ManyToOne(targetEntity: Institution::class, inversedBy: "collections")]
    #[ORM\JoinColumn(name: 'source_id', referencedColumnName: 'MetadataID')]
    protected(set) Institution $institution;

    //TODO performance killer
//    #[ORM\OneToOne(targetEntity: IiifDefinition::class, mappedBy: 'herbCollection')]
//    protected(set) ?IiifDefinition $iiifDefinition = null;

    #[ORM\OneToMany(targetEntity: Specimens::class, mappedBy: "herbCollection")]
    protected(set) Collection $specimens;

    public function __construct()
    {
        $this->specimens = new ArrayCollection();
    }

}
