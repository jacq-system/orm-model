<?php declare(strict_types=1);

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
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'SourceInstitutionID')]
    protected(set) string $code;

    #[ORM\Column(name: 'LicenseURI')]
    protected(set) ?string $licenseUri;

    #[ORM\Column(name: 'OwnerLogoURI')]
    protected(set) ?string $ownerLogoUri;

    #[ORM\Column(name: 'OwnerOrganizationAbbrev')]
    protected(set) ?string $abbreviation;

    #[ORM\Column(name: 'OwnerOrganizationName')]
    protected(set) ?string $name;

    #[ORM\Column(name: 'SourceID')]
    protected(set) string $name2;

    #[ORM\OneToOne(targetEntity: ImageDefinition::class, mappedBy: 'institution')]
    protected(set) ?ImageDefinition $imageDefinition = null;

    #[ORM\OneToMany(targetEntity: HerbCollection::class, mappedBy: "institution")]
    protected(set) Collection $collections;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: "institutions")]
    #[ORM\JoinColumn(name: 'nationID_fk', referencedColumnName: 'NationID', nullable: true)]
    protected(set) ?Country $country = null;

    #[ORM\Column(name: "IH_link", nullable: true)]
    protected(set) ?string $IHLink = null;

    #[ORM\Column(name: "IH_description", nullable: true)]
    protected(set) ?string $IHDescription = null;

    #[Column(name: 'DateCreated', type: Types::DATETIME_IMMUTABLE, nullable: false)]
    protected(set) \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->collections = new ArrayCollection();
    }

}
