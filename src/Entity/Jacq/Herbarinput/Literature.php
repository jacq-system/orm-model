<?php declare(strict_types=1);

namespace JACQ\Entity\Jacq\Herbarinput;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JACQ\Repository\Herbarinput\LiteratureRepository;

#[ORM\Entity(repositoryClass: LiteratureRepository::class)]
#[ORM\Table(name: 'tbl_lit', schema: 'herbarinput')]
class Literature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'citationID')]
    protected(set) ?int $id = null;

    #[ORM\Column(name: 'hideScientificNameAuthors')]
    protected(set) bool $hideScientificNameAuthors;

    #[ORM\Column(name: 'periodicalID')]
    protected(set) ?int $periodical;

    #[ORM\OneToMany(targetEntity: Synonymy::class, mappedBy: "literature")]
    protected(set) Collection $synonymies;

    public function __construct()
    {
        $this->synonymies = new ArrayCollection();
    }

}
