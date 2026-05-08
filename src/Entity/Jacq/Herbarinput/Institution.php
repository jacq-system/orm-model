<?php

declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use JACQ\Repository\Herbarinput\InstitutionRepository;

#[ORM\Entity(repositoryClass: InstitutionRepository::class)]
#[ORM\Table(name: 'metadata', schema: 'herbarinput')]
class Institution
{
    public const int WU = 1;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'MetadataID')]
    public protected(set) ?int $id = null;

    #[ORM\Column(name: 'SourceInstitutionID')]
    public protected(set) string $code;

    #[ORM\Column(name: 'LicenseURI')]
    public protected(set) ?string $licenseUri;

    #[ORM\Column(name: 'OwnerLogoURI')]
    public protected(set) ?string $ownerLogoUri;

    #[ORM\Column(name: 'OwnerOrganizationAbbrev')]
    public protected(set) ?string $abbreviation;

    #[ORM\Column(name: 'OwnerOrganizationName')]
    public protected(set) ?string $name;

    #[ORM\Column(name: 'SourceID')]
    public protected(set) string $name2;

    //TODO performance killer
    //    #[ORM\OneToOne(targetEntity: ImageDefinition::class, mappedBy: 'institution')]
    //    protected(set) ?ImageDefinition $imageDefinition = null;

    //TODO performance killer
    //    #[ORM\OneToOne(targetEntity: IiifDefinition::class, mappedBy: 'institution')]
    //    protected(set) ?IiifDefinition $iiifDefinition = null;


    /**
         * @var Collection<int, HerbCollection>
         */
    #[ORM\OneToMany(targetEntity: HerbCollection::class, mappedBy: "institution")]
    public protected(set) Collection $collections;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: "institutions")]
    #[ORM\JoinColumn(name: 'nationID_fk', referencedColumnName: 'NationID', nullable: true)]
    public protected(set) ?Country $country = null;

    #[ORM\Column(name: "IH_link", nullable: true)]
    public protected(set) ?string $IHLink = null;

    #[ORM\Column(name: "IH_description", nullable: true)]
    public protected(set) ?string $IHDescription = null;

    #[Column(name: 'DateCreated', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    public protected(set) \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->collections = new ArrayCollection();
    }

}
