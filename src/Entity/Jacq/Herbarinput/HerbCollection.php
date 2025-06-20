<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use JACQ\Entity\Jacq\HerbarPictures\IiifDefinition;
use JACQ\Repository\Herbarinput\HerbCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HerbCollectionRepository::class)]
#[ORM\Table(name: 'tbl_management_collections', schema: 'herbarinput')]
class HerbCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'collectionID')]
    private ?int $id = null;

    #[ORM\Column(name: 'collection')]
    private string $name;

    #[ORM\Column(name: 'coll_short_prj')]
    private string $collShortPrj;

    #[ORM\Column(name: 'coll_short')]
    private string $collShort;

    #[ORM\Column(name: 'picture_filename')]
    private ?string $pictureFilename = null;


    #[ORM\ManyToOne(targetEntity: Institution::class)]
    #[ORM\JoinColumn(name: 'source_id', referencedColumnName: 'MetadataID')]
    private Institution $institution;

    #[ORM\OneToOne(targetEntity: IiifDefinition::class, mappedBy: 'herbCollection')]
    private ?IiifDefinition $iiifDefinition = null;

    #[ORM\OneToMany(targetEntity: Specimens::class, mappedBy: "herbCollection")]
    private Collection $specimens;

    public function __construct()
    {
        $this->specimens = new ArrayCollection();
    }

    public function getInstitution(): Institution
    {
        return $this->institution;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPictureFilename(): ?string
    {
        return $this->pictureFilename;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCollShortPrj(): string
    {
        return $this->collShortPrj;
    }

    public function getIiifDefinition(): ?IiifDefinition
    {
        return $this->iiifDefinition;
    }

    public function getCollShort(): string
    {
        return $this->collShort;
    }


}
