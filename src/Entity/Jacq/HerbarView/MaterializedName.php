<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\HerbarView;

use Doctrine\ORM\Mapping as ORM;
use JACQ\Entity\Jacq\Herbarinput\Species;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'view_scientificName_mtrlzd', schema: 'herbar_view')]
class MaterializedName
{

    #[ORM\Id]
    #[ORM\OneToOne(
        targetEntity: Species::class,
        inversedBy: 'materializedName'
    )]
    #[ORM\JoinColumn(name: 'scientific_name_id', referencedColumnName: 'taxonID')]
    private Species $taxon;

    #[ORM\Column(name: 'scientific_name')]
    protected(set) string $scientificName;

    #[ORM\Column(name: 'scientific_name_no_author')]
    protected(set) string $scientificNameWithoutAuthor;
}
