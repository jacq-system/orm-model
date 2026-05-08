<?php

declare(strict_types=1);

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
    public protected(set) ?int $id = null;
    #[ORM\Column(name: 'iiif_capable')]
    public protected(set) bool $iiifCapable;

    #[ORM\Column(name: 'img_coll_short')]
    public protected(set) string $abbreviation;

    #[ORM\Column(name: 'iiif_url')]
    public protected(set) ?string $iiifUrl = null;

    #[ORM\Column(name: 'imgserver_url')]
    public protected(set) ?string $imageserverUrl = null;

    #[ORM\Column(name: 'HerbNummerNrDigits')]
    public protected(set) int $herbNummerNrDigits;

    #[ORM\Column(name: 'imgserver_type')]
    public protected(set) string $serverType;

    #[ORM\Column(name: 'key')]
    public protected(set) string $apiKey;

    #[ORM\OneToOne(targetEntity: Institution::class)]
    #[ORM\JoinColumn(name: 'source_id_fk', referencedColumnName: 'MetadataID')]
    public protected(set) Institution|null $institution = null;

}
