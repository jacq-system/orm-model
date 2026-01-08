<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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

    public function __construct()
    {
        $this->collections = new ArrayCollection();
    }

}
