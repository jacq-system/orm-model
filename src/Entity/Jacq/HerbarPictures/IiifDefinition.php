<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\HerbarPictures;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Entity\Jacq\Herbarinput\HerbCollection;

#[ORM\Entity]
#[ORM\Table(name: 'iiif_definition', schema: 'herbar_pictures')]
class IiifDefinition
{

    #[ORM\Column(name: 'manifest_uri')]
    protected(set) string $manifestUri;

    #[ORM\Column(name: 'manifest_backend')]
    protected(set) ?string $manifestBackend = null;

    //TODO wrong mapping -
    // The referenced column name 'source_id' has to be a primary key column on the target entity class 'JACQ\Entity\Jacq\Herbarinput\HerbCollection'

    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: HerbCollection::class, inversedBy: 'iiifDefinition')]
    #[ORM\JoinColumn(name: 'source_id_fk', referencedColumnName: 'source_id')]
    protected(set) HerbCollection $herbCollection;

}
