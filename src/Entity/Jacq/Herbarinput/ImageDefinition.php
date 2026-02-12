<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\ImageDefinitionRepository;

#[ORM\Entity(repositoryClass: ImageDefinitionRepository::class)]
#[ORM\Table(name: 'tbl_img_definition', schema: 'herbarinput')]
class ImageDefinition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'img_def_ID')]
    protected(set) ?int $id = null;
    #[ORM\Column(name: 'iiif_capable')]
    protected(set) bool $iiifCapable;

    #[ORM\Column(name: 'img_coll_short')]
    protected(set) string $abbreviation;

    #[ORM\Column(name: 'iiif_url')]
    protected(set) ?string $iiifUrl = null;

    #[ORM\Column(name: 'imgserver_url')]
    protected(set) ?string $imageserverUrl = null;

    #[ORM\Column(name: 'HerbNummerNrDigits')]
    protected(set) int $herbNummerNrDigits;

    #[ORM\Column(name: 'imgserver_type')]
    protected(set) string $serverType;

    #[ORM\Column(name: 'key')]
    protected(set) string $apiKey;

    #[ORM\OneToOne(targetEntity: Institution::class)]
    #[ORM\JoinColumn(name: 'source_id_fk', referencedColumnName: 'MetadataID')]
    protected(set) Institution|null $institution = null;

}
