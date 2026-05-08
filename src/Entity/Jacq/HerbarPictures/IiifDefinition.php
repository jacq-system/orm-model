<?php

declare(strict_types=1);

namespace JACQ\Entity\Jacq\HerbarPictures;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Entity\Jacq\Herbarinput\Institution;

#[ORM\Entity]
#[ORM\Table(name: 'iiif_definition', schema: 'herbar_pictures')]
class IiifDefinition
{
    #[ORM\Column(name: 'manifest_uri')]
    public protected(set) string $manifestUri;

    #[ORM\Column(name: 'manifest_backend')]
    public protected(set) ?string $manifestBackend = null;

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Institution::class)]
    #[ORM\JoinColumn(name: 'source_id_fk', referencedColumnName: 'MetadataID')]
    public protected(set) Institution $institution;

}
