<?php

declare(strict_types=1);

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
    public protected(set) ?int $id = null;

    #[ORM\Column(name: 'collection')]
    public protected(set) string $name;

    #[ORM\Column(name: 'coll_short_prj')]
    public protected(set) string $collShortPrj;

    #[ORM\Column(name: 'coll_short')]
    public protected(set) string $collShort;

    #[ORM\Column(name: 'picture_filename')]
    public protected(set) ?string $pictureFilename = null;

    #[ORM\ManyToOne(targetEntity: Institution::class, inversedBy: "collections")]
    #[ORM\JoinColumn(name: 'source_id', referencedColumnName: 'MetadataID')]
    public protected(set) Institution $institution;

    /**
     * @var Collection<int, Specimens>
     */
    #[ORM\OneToMany(targetEntity: Specimens::class, mappedBy: "herbCollection")]
    public protected(set) Collection $specimens;

    public function __construct()
    {
        $this->specimens = new ArrayCollection();
    }

}
